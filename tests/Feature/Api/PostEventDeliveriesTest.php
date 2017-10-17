<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostEventDeliveriesTest extends TestCase
{
    use RefreshDatabase;

    protected $date;
    protected $event;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->date = Carbon::now()->addWeek();
        $this->user = create('App\User');
        $this->event = create('App\Event', ['account_id' => $this->user->account->id]);
        $this->request = [
            'address' => 'some fake address',
            'deliver_on' => $this->date->toRfc3339String(),
            'description' => 'some description',
            'fee' => 10,
        ];
    }

    protected function url($id)
    {
        return '/api/events/' . $id . '/deliveries';
    }

    /** @test */
    public function a_user_can_add_deliveries_to_an_event()
    {
        $this->assertEquals(0, $this->event->deliveries->count());

        $this->signIn($this->user)
            ->postJson($this->url($this->event->id), $this->request)
    		->assertStatus(200);

        $delivery = $this->event->fresh()->deliveries->first();
        $this->assertEquals(1, $this->event->fresh()->deliveries->count());
        $this->assertEquals($this->request['address'], $delivery->address);
        $this->assertEquals(
            $this->date->toRfc3339String(),
            $delivery->deliver_on->toRfc3339String()
        );
        $this->assertEquals($this->request['description'], $delivery->description);
        $this->assertEquals($this->request['fee'], $delivery->fee);
    }

    /** @test */
    public function a_user_can_only_add_deliveries_to_events_in_their_account()
    {
        $someOtherEvent = create('App\Event');

        $this->signIn($this->user)
            ->postJson($this->url($someOtherEvent->id), $this->request)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_add_deliveries_to_existing_events()
    {
        $badEventId = 666;

        $this->signIn($this->user)
            ->postJson($this->url($badEventId), $this->request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_deliveries()
    {
        $this->postJson($this->url($this->event->id), $this->request)
            ->assertStatus(401);
    }
}
