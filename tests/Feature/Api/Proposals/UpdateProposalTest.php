<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProposalTest extends TestCase
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
        ], 2);
    }

    /** @test */
    public function a_user_can_activate_a_proposal_for_an_event()
    {
        $this->assertTrue($this->proposals[1]->isActive);

        $this->activateProposal($this->event->id, $this->proposals[0]->id)
            ->assertStatus(200);

        $this->assertTrue($this->proposals[0]->isActive);
        $this->assertFalse($this->proposals[1]->isActive);
    }

    /** @test */
    public function users_cannot_activate_proposals_from_other_events()
    {
        $otherEvent = create('App\Event', [
            'account_id' => $this->event->account->id,
        ])->id;

        $this->activateProposal($otherEvent, $this->proposals[0]->id)
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_activate_proposals_for_events_in_other_accounts()
    {
        $eventInOtherAccount = create('App\Event')->id;
        $otherProposals = create('App\Proposal', [
            'event_id' => $eventInOtherAccount,
        ], 2);

        $this->activateProposal($eventInOtherAccount, $otherProposals[0]->id)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_activate_proposals_for_invalid_events()
    {
        $badId = 666;

        $this->activateProposal($badId, $this->proposals[0]->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_activate_proposals()
    {
        $this->activateProposal($this->event->id, $this->proposals[0]->id, false)
            ->assertStatus(401);
    }

    protected function activateProposal($eventId, $id, $signIn = true)
    {
        $url = '/api/events/' . $eventId . '/proposals/' . $id;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->event->id,
            ]));
        }

        return $this->patchJson($url);
    }
}
