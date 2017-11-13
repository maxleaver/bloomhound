<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetProposalsTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $proposals;

    protected function setUp()
    {
        parent::setUp();

        $this->event = create('App\Event');
        $this->proposals = create('App\Proposal', [
            'event_id' => $this->event->id,
        ], 3);
    }

    /** @test */
    public function a_user_can_get_a_list_of_proposals_in_their_account()
    {
        $otherProposals = create('App\Proposal', [], 2);

        $response = $this->getProposals()
            ->assertStatus(200)
            ->getData();

        $this->assertEquals(4, count($response));
        $this->assertEquals(1, $response[0]->id);
        $this->assertEquals(2, $response[1]->id);
        $this->assertEquals(3, $response[2]->id);
        $this->assertEquals(4, $response[3]->id);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_a_list_of_proposals()
    {
        $this->getProposals(null, false)->assertStatus(401);
    }

    /** @test */
    public function a_user_can_get_a_specific_proposal()
    {
        $response = $this->getProposals($this->proposals[0]->id)
            ->assertStatus(200)
            ->getData();

        $this->assertEquals(1, count($response->data));
        $this->assertEquals($this->proposals[0]->id, $response->data->id);
        $this->assertEquals($this->proposals[0]->version, $response->data->version);
    }

    /** @test */
    public function users_can_only_see_a_proposal_in_their_account()
    {
        $otherProposal = create('App\Proposal');

        $this->getProposals($otherProposal->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_a_specific_proposal()
    {
        $this->getProposals($this->proposals[0]->id, false)->assertStatus(401);
    }

    protected function getProposals($id = null, $signIn = true)
    {
        $url = 'api/events/' . $this->event->id . '/proposals';

        if ($id) {
            $url = 'api/proposals/' . $id;
        }

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->event->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
