<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostEventSetupsTest extends TestCase
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
            'setup_on' => $this->date->toRfc3339String(),
            'description' => 'some description',
            'fee' => 10,
        ];
    }

    protected function url($id)
    {
        return '/api/events/' . $id . '/setups';
    }

    /** @test */
    public function a_user_can_add_setups_to_an_event()
    {
        $this->assertEquals(0, $this->event->setups->count());

        $this->signIn($this->user)
            ->postJson($this->url($this->event->id), $this->request)
    		->assertStatus(200);

        $setup = $this->event->fresh()->setups->first();
        $this->assertEquals(1, $this->event->fresh()->setups->count());
        $this->assertEquals($this->request['address'], $setup->address);
        $this->assertEquals(
            $this->date->toRfc3339String(),
            $setup->setup_on->toRfc3339String()
        );
        $this->assertEquals($this->request['description'], $setup->description);
        $this->assertEquals($this->request['fee'], $setup->fee);
    }

    /** @test */
    public function a_user_can_only_add_setups_to_events_in_their_account()
    {
        $someOtherEvent = create('App\Event');

        $this->signIn($this->user)
            ->postJson($this->url($someOtherEvent->id), $this->request)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_add_setups_to_existing_events()
    {
        $badEventId = 666;

        $this->signIn($this->user)
            ->postJson($this->url($badEventId), $this->request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_setups()
    {
        $this->postJson($this->url($this->event->id), $this->request)
            ->assertStatus(401);
    }
}
