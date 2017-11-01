<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetEventNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $notes;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->event = create('App\Event', ['account_id' => $this->user->account->id]);
        $this->notes = create('App\Note', [
        	'notable_id' => $this->event->id,
        	'notable_type' => 'App\Event'
        ], 3);
    }

    /** @test */
    public function a_user_can_view_notes_for_a_event()
    {
    	$someOtherNote = create('App\Note');

        $this->signIn($this->user)
            ->getJson($this->getUrl($this->event->id))
    		->assertStatus(200)
    		->assertJsonFragment([$this->notes[0]->text])
    		->assertJsonFragment([$this->notes[1]->text])
            ->assertJsonMissing([$someOtherNote->text]);
    }

    /** @test */
    public function a_user_can_only_view_notes_for_events_in_their_account()
    {
        $otherEvent = create('App\Event');
        $otherNotes = create('App\Note', ['notable_id' => $otherEvent->id, 'notable_type' => 'App\Event'], 3);

        $this->signIn($this->user)
            ->getJson($this->getUrl($otherEvent->id))
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_notes()
    {
        $this->getJson($this->getUrl($this->event->id))
            ->assertStatus(401);
    }

    protected function getUrl($id)
    {
        return '/api/events/' . $id . '/notes';
    }
}
