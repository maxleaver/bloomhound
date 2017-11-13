<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostProposalSetupsTest extends TestCase
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
            'setup_on' => Carbon::now()->addWeek()->toRfc3339String(),
            'description' => 'some description',
            'fee' => 10,
        ];
    }

    /** @test */
    public function a_user_can_add_setups_to_a_proposal()
    {
        $this->assertEquals(0, $this->proposal->setups->count());

        $this->createSetup($this->proposal->id)
            ->assertStatus(200);

        $setup = $this->proposal->fresh()->setups->first();
        $this->assertEquals(1, $this->proposal->fresh()->setups->count());
        $this->assertEquals($this->request['address'], $setup->address);
        $this->assertEquals(
            $this->request['setup_on'],
            $setup->setup_on->toRfc3339String()
        );
        $this->assertEquals($this->request['description'], $setup->description);
        $this->assertEquals($this->request['fee'], $setup->fee);
    }

    /** @test */
    public function users_can_only_add_setups_to_a_proposal_in_their_account()
    {
        $someOtherProposal = create('App\Proposal')->id;
        $this->createSetup($someOtherProposal)
            ->assertStatus(404);
    }

    /** @test */
    public function user_can_only_add_setups_to_an_existing_proposal()
    {
        $badId = 666;
        $this->createSetup($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_setups()
    {
        $this->createSetup($this->proposal->id, false)
            ->assertStatus(401);
    }

    protected function createSetup($id, $signIn = true)
    {
        $url = 'api/proposals/' . $id . '/setups';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        return $this->postJson($url, $this->request);
    }
}
