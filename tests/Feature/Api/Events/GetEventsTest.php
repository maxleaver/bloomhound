<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetEventsTest extends TestCase
{
    use RefreshDatabase;

    protected $events;
    protected $otherEvents;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->events = create('App\Event', [
            'account_id' => $this->user->account->id,
        ], 3);
        $this->otherEvents = create('App\Event', [], 3);
    }

    /** @test */
    public function a_user_can_get_a_list_of_events_for_their_account()
    {
    	$this->getEvents()
    		->assertStatus(200)
    		->assertJsonFragment([$this->events[0]->name])
    		->assertJsonFragment([$this->events[1]->name])
    		->assertJsonFragment([$this->events[2]->name])
            ->assertJsonMissing([$this->otherEvents[0]->name]);
    }

    /** @test */
    public function unauthorized_users_cannot_get_events()
    {
        $this->getEvents(false, true)
    		->assertStatus(401);
    }

    protected function getEvents($signIn = true, $withJson = false)
    {
        $url = '/api/events';

        if ($signIn) {
            $this->signIn($this->user);
        }

        if ($withJson) {
            return $this->getJson($url);
        }

        return $this->get($url);
    }
}
