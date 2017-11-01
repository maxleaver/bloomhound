<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostEventVendorsTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->event = create('App\Event', ['account_id' => $this->user->account->id]);
        $this->request = [
            'vendor_id' => create('App\Vendor', ['account_id' => $this->user->account->id])->id,
        ];
    }

    /** @test */
    public function a_user_can_add_an_existing_vendor_to_an_event()
    {
        $this->assertEquals($this->event->vendors()->count(), 0);

        $this->signIn($this->user)
            ->postJson($this->url($this->event->id), $this->request)
            ->assertStatus(200);

        $this->assertEquals($this->event->vendors()->count(), 1);
    }

    /** @test */
    public function a_user_can_add_a_new_vendor_to_an_event()
    {
        $request = [
            'vendor_name' => 'My new vendor'
        ];

        $this->assertEquals($this->event->vendors()->count(), 0);

        $this->signIn($this->user)
            ->postJson($this->url($this->event->id), $request)
            ->assertStatus(200);

        $this->assertEquals($this->event->vendors()->count(), 1);
    }

    /** @test */
    public function users_cannot_add_vendors_from_other_accounts_to_events()
    {
        $request = [
            'vendor_id' => create('App\Vendor')->id
        ];

        $this->signIn($this->user)
            ->postJson($this->url($this->event->id), $request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_add_vendors_to_events_in_other_accounts()
    {
        $eventInAnotherAccount = create('App\Event');

        $this->signIn($this->user)
            ->postJson($this->url($eventInAnotherAccount->id), $this->request)
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_vendors()
    {
        $this->postJson($this->url($this->event->id), $this->request)
            ->assertStatus(401);
    }

    protected function url($id)
    {
        return 'api/events/' . $id . '/vendors';
    }
}
