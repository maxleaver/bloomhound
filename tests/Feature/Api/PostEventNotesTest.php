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
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->request = ['text' => 'This is my note'];
        $this->user = create('App\User');
        $this->event = create('App\Event', ['account_id' => $this->user->account->id]);
    }

    /** @test */
    public function a_user_can_add_notes_to_an_event()
    {
    	$this->assertEquals($this->event->notes()->count(), 0);

        Passport::actingAs($this->user);
        $response = $this->json('POST', $this->getUrl($this->event->id), $this->request)
    		->assertStatus(200);

        $this->assertEquals($this->event->notes()->count(), 1);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_events_assigned_to_their_account()
    {
    	$event = create('App\Event');

        Passport::actingAs($this->user);
        $response = $this->json('POST', $this->getUrl($event->id), $this->request)
    		->assertStatus(404);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_events_that_exist()
    {
        Passport::actingAs($this->user);
        $response = $this->json('POST', $this->getUrl(123), $this->request)
    		->assertStatus(404);
    }

    protected function getUrl($id)
    {
        return '/api/events/' . $id . '/notes';
    }
}
