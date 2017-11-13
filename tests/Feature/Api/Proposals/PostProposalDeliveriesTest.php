<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostProposalDeliveriesTest extends TestCase
{
    use RefreshDatabase;

    protected $proposal;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
        $this->request = [
            'address' => 'some fake address',
            'deliver_on' => Carbon::now()->addWeek()->toRfc3339String(),
            'description' => 'some description',
            'fee' => 10,
        ];
    }

    /** @test */
    public function a_user_can_add_deliveries_to_a_proposal()
    {
        $this->assertEquals(0, $this->proposal->deliveries->count());

        $this->createDelivery($this->proposal->id)
            ->assertStatus(200);

        $delivery = $this->proposal->fresh()->deliveries->first();
        $this->assertEquals(1, $this->proposal->fresh()->deliveries->count());
        $this->assertEquals($this->request['address'], $delivery->address);
        $this->assertEquals(
            $this->request['deliver_on'],
            $delivery->deliver_on->toRfc3339String()
        );
        $this->assertEquals($this->request['description'], $delivery->description);
        $this->assertEquals($this->request['fee'], $delivery->fee);
    }

    /** @test */
    public function a_user_can_include_arrangements_with_a_delivery()
    {
        $arrangements = create('App\Arrangement', [
            'account_id' => $this->proposal->event->account->id,
            'proposal_id' => $this->proposal->id
        ], 5);

        $this->request['arrangements'] = $arrangements->pluck('id');

        $response = $this->createDelivery($this->proposal->id)
            ->assertStatus(200);

        $delivery = \App\Delivery::find($response->getData()->id);
        $this->assertEquals(5, $delivery->arrangements->count());
    }

    /** @test */
    public function a_user_can_only_add_arrangements_in_the_same_proposal()
    {
        $otherArrangements = create('App\Arrangement', [
            'account_id' => $this->proposal->event->account->id
        ], 3);

        $this->request['arrangements'] = $otherArrangements->pluck('id');

        $this->createDelivery($this->proposal->id)
            ->assertStatus(422);
    }

    /** @test */
    public function users_can_only_add_deliveries_to_proposals_in_their_account()
    {
        $someOtherProposal = create('App\Proposal')->id;
        $this->createDelivery($someOtherProposal)
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_only_add_deliveries_to_existing_proposals()
    {
        $badId = 666;
        $this->createDelivery($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_deliveries()
    {
        $this->createDelivery($this->proposal->id, false)
            ->assertStatus(401);
    }

    protected function createDelivery($id, $signIn = true)
    {
        $url = 'api/proposals/' . $id . '/deliveries';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        return $this->postJson($url, $this->request);
    }
}
