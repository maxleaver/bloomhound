<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetProposalSetupsTest extends TestCase
{
    use RefreshDatabase;

    protected $proposal;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
    }

    /** @test */
    public function a_user_can_get_a_list_of_setups_for_a_proposal()
    {
        $setups = create('App\Setup', [
            'account_id' => $this->proposal->event->account->id,
            'proposal_id' => $this->proposal->id,
        ], 3);

        $someOtherSetup = create('App\Setup', [
            'account_id' => $this->proposal->event->account->id
        ]);

        $this->getSetups($this->proposal->id)
    		->assertStatus(200)
    		->assertJsonFragment([$setups[0]->description])
    		->assertJsonFragment([$setups[1]->description])
            ->assertJsonMissing([$someOtherSetup->description]);
    }

    /** @test */
    public function a_user_can_only_get_setups_for_a_proposal_in_their_account()
    {
        $someOtherProposal = create('App\Proposal');
        $this->getSetups($someOtherProposal->id)
            ->assertStatus(404);
    }

    /** @test */
    public function a_user_can_only_get_setups_for_an_existing_proposal()
    {
    	$badId = 123;
        $this->getSetups($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_setups()
    {
        $this->getSetups($this->proposal->id, false)
            ->assertStatus(401);
    }

    protected function getSetups($id, $signIn = true)
    {
        $url = 'api/proposals/' . $id . '/setups';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
