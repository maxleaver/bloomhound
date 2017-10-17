<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetEventDeliveriesTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->event = create('App\Event', ['account_id' => $this->user->account->id]);
    }

    protected function url($id)
    {
        return '/api/events/' . $id . '/deliveries';
    }

    /** @test */
    public function a_user_can_get_a_list_of_scheduled_deliveries_for_an_event()
    {
        $deliveries = create('App\Delivery', [
            'account_id' => $this->user->account->id,
            'event_id' => $this->event->id,
        ], 3);

        $someOtherDelivery = create('App\Delivery', [
            'account_id' => $this->user->account->id
        ]);

        $this->signIn($this->user)
            ->getJson($this->url($this->event->id))
    		->assertStatus(200)
    		->assertJsonFragment([$deliveries[0]->description])
    		->assertJsonFragment([$deliveries[1]->description])
            ->assertJsonMissing([$someOtherDelivery->description]);
    }

    /** @test */
    public function a_user_can_only_get_deliveries_for_events_in_their_account()
    {
        $someOtherEvent = create('App\Event');

        $this->signIn($this->user)
            ->getJson($this->url($someOtherEvent->id))
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_get_deliveries_for_existing_events()
    {
    	$clearlyInvalidEventId = 123;

    	$this->signIn($this->user)
            ->getJson($this->url($clearlyInvalidEventId))
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_deliveries()
    {
        $this->getJson($this->url($this->event->id))
            ->assertStatus(401);
    }
}
