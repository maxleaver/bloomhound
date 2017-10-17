<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetEventArrangementsTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangements;
    protected $event;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->event = create('App\Event', ['account_id' => $this->user->account->id]);
        $this->arrangements = create('App\Arrangement', [
            'account_id' => $this->user->account->id,
            'event_id' => $this->event->id,
        ], 10);
    }

    protected function url($id)
    {
        return '/api/events/' . $id . '/arrangements';
    }

    /** @test */
    public function a_user_can_get_a_list_of_arrangements_for_an_event()
    {
        $someOtherArrangement = create('App\Arrangement', ['account_id' => $this->user->account->id]);

        $this->signIn($this->user)
            ->getJson($this->url($this->event->id))
    		->assertStatus(200)
    		->assertJsonFragment([$this->arrangements[0]->name])
    		->assertJsonFragment([$this->arrangements[1]->name])
            ->assertJsonMissing([$someOtherArrangement->name]);
    }

    /** @test */
    public function a_user_can_only_get_arrangements_for_events_in_their_account()
    {
    	$someOtherEvent = create('App\Event');

    	$this->signIn($this->user)
            ->getJson($this->url($someOtherEvent->id))
    		->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_get_arrangements_for_events_that_exist()
    {
    	$clearlyInvalidEventId = 123;

    	$this->signIn($this->user)
            ->getJson($this->url($clearlyInvalidEventId))
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_arrangements()
    {
        $this->getJson($this->url($this->event->id))
            ->assertStatus(401);
    }
}
