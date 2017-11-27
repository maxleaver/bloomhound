<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetEventNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $notes;

    protected function setUp()
    {
        parent::setUp();

        $this->event = create('App\Event');
        $this->notes = create('App\Note', [
            'notable_id' => $this->event->id,
            'notable_type' => 'App\Event'
        ], 3);
    }

    /** @test */
    public function a_user_can_view_notes_for_a_event()
    {
        $someOtherNote = create('App\Note');

        $this->getNotes($this->event->id)
        // $this->signIn($this->user)
        //     ->getJson($this->getUrl($this->event->id))
            ->assertStatus(200)
            ->assertJsonFragment([$this->notes[0]->text])
            ->assertJsonFragment([$this->notes[1]->text])
            ->assertJsonMissing([$someOtherNote->text]);
    }

    /** @test */
    public function a_user_can_only_view_notes_for_events_in_their_account()
    {
        $this->getNotes(create('App\Event')->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_notes()
    {
        $this->getNotes($this->event->id, false)
            ->assertStatus(401);
    }

    protected function getNotes($id, $signIn = true)
    {
        $url = '/api/events/' . $id . '/notes';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->event->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
