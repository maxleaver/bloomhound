<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEventTest extends TestCase
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
        return 'api/events/' . $id;
    }

    /** @test */
    public function users_can_update_an_event()
    {
        $name = 'Test Event';
        $date = Carbon::now()->addDays(10)->toDateTimeString();
        $request = compact('name', 'date');

        $this->signIn($this->user)
            ->patchJson($this->url($this->event->id), $request)
            ->assertStatus(200);

        $event = $this->event->fresh();
        $this->assertEquals($name, $event->name);
        $this->assertEquals($date, $event->date);
    }

    /** @test */
    public function users_can_only_update_events_in_their_account()
    {
        $eventInAnotherAccount = create('App\Event');
        $name = 'New Name';
        $date = Carbon::now()->addDays(10)->toDateTimeString();
        $request = compact('name', 'date');

        $this->signIn($this->user)
            ->patchJson($this->url($eventInAnotherAccount->id), $request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_update_events_that_exist()
    {
        $badEventId = 123;
        $name = 'New Name';
        $date = Carbon::now()->addDays(10)->toDateTimeString();
        $request = compact('name', 'date');

        $this->signIn($this->user)
            ->patchJson($this->url($badEventId), $request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_events()
    {
        $name = 'New Name';
        $date = Carbon::now()->addDays(10)->toDateTimeString();
        $request = compact('name', 'date');

        $this->patchJson($this->url($this->event->id), $request)
            ->assertStatus(401);
    }
}
