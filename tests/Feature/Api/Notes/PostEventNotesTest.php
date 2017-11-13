<?php

namespace Tests\Feature\Api;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostEventNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->event = create('App\Event');
        $this->request = ['text' => 'This is my note'];
    }

    /** @test */
    public function a_user_can_add_notes_to_an_event()
    {
    	$this->assertEquals($this->event->notes()->count(), 0);

        $this->addNote($this->event->id)
    		->assertStatus(200);

        $this->assertEquals($this->event->notes()->count(), 1);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_events_assigned_to_their_account()
    {
    	$event = create('App\Event');
        $this->addNote(create('App\Event')->id)
    		->assertStatus(404);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_events_that_exist()
    {
        $badId = 666;
        $this->addNote($badId)
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_post_events()
    {
        $this->addNote($this->event->id, false)
            ->assertStatus(401);
    }

    protected function addNote($id, $signIn = true)
    {
        $url = '/api/events/' . $id . '/notes';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->event->account->id,
            ]));
        }

        return $this->postJson($url, $this->request);
    }
}
