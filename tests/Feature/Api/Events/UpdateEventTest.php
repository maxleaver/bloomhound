<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEventTest extends TestCase
{
	use RefreshDatabase;

    protected $event;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->event = create('App\Event');
        $this->request = [
            'name' => 'test event',
            'date' => Carbon::now()->addDays(10)->toDateTimeString(),
        ];
    }

    /** @test */
    public function users_can_update_an_event()
    {
        $this->updateEvent($this->event->id)
            ->assertStatus(200);

        $event = $this->event->fresh();
        $this->assertEquals($this->request['name'], $event->name);
        $this->assertEquals($this->request['date'], $event->date);
    }

    /** @test */
    public function users_can_only_update_events_in_their_account()
    {
        $eventInAnotherAccount = create('App\Event')->id;

        $this->updateEvent($eventInAnotherAccount)
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_update_events_that_exist()
    {
        $badEventId = 123;

        $this->updateEvent($badEventId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_events()
    {
        $this->updateEvent($this->event->id, false, true)
            ->assertStatus(401);
    }

    protected function updateEvent($id, $signIn = true, $withJson = false)
    {
        $url = '/api/events/' . $id;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->event->account->id,
            ]));
        }

        if ($withJson) {
            return $this->patchJson($url, $this->request);
        }

        return $this->patch($url, $this->request);
    }
}
