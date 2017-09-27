<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostEventArrangementsTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->request = ['name' => 'Some arrangement name', 'quantity' => 10];
        $this->user = create('App\User');
        $this->event = create('App\Event', ['account_id' => $this->user->account->id]);
    }

    protected function getUrl($id)
    {
        return 'api/events/' . $id . '/arrangements';
    }

    /** @test */
    public function users_can_create_flower_arrangements_for_an_event()
    {
    	$this->assertEquals($this->event->arrangements()->count(), 0);

        $this->signIn($this->user)
            ->postJson($this->getUrl($this->event->id), $this->request)
    		->assertStatus(200);

        $this->assertEquals($this->event->arrangements()->count(), 1);
    }

    /** @test */
    public function users_can_only_create_arrangements_for_valid_events()
    {
        $clearlyInvalidEventId = 123;

        $this->signIn($this->user)
            ->postJson($this->getUrl($clearlyInvalidEventId), $this->request)
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_only_create_arrangements_for_events_in_their_account()
    {
        $eventInAnotherAccount = create('App\Event');

        $this->signIn($this->user)
            ->postJson($this->getUrl($eventInAnotherAccount->id), $this->request)
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_create_arrangements()
    {
        $this->postJson($this->getUrl($this->event->id), $this->request)
            ->assertStatus(401);
    }
}
