<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetProposalDeliveriesTest extends TestCase
{
    use RefreshDatabase;

    protected $proposal;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
    }

    /** @test */
    public function a_user_can_get_a_list_of_scheduled_deliveries_for_a_proposal()
    {
        $deliveries = create('App\Delivery', [
            'account_id' => $this->proposal->event->account->id,
            'proposal_id' => $this->proposal->id,
        ], 3);

        $someOtherDelivery = create('App\Delivery', [
            'account_id' => $this->proposal->event->account->id
        ]);

        $this->getDeliveries($this->proposal->id)
            ->assertStatus(200)
            ->assertJsonFragment([$deliveries[0]->description])
            ->assertJsonFragment([$deliveries[1]->description])
            ->assertJsonMissing([$someOtherDelivery->description]);
    }

    /** @test */
    public function a_user_can_only_get_deliveries_for_a_proposal_in_their_account()
    {
        $someOtherProposal = create('App\Proposal');
        $this->getDeliveries($someOtherProposal->id)
            ->assertStatus(404);
    }

    /** @test */
    public function a_user_can_only_get_deliveries_for_an_existing_proposal()
    {
        $badId = 666;
        $this->getDeliveries($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_deliveries()
    {
        $this->getDeliveries($this->proposal->id, false)
            ->assertStatus(401);
    }

    protected function getDeliveries($id, $signIn = true)
    {
        $url = 'api/proposals/' . $id . '/deliveries';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
