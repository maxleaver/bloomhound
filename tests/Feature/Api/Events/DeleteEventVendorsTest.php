<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteEventVendorsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->event = create('App\Event', ['account_id' => $this->user->account->id]);
        $this->vendor = create('App\Vendor', ['account_id' => $this->user->account->id]);
        $this->vendor->events()->attach($this->event);
    }

    protected function makeRequest($eventId, $vendorId, $signIn = true)
    {
        $url = 'api/events/' . $eventId . '/vendors/' . $vendorId;

        if ($signIn) {
            return $this->signIn($this->user)->deleteJson($url);
        }

        return $this->deleteJson($url);
    }

    /** @test */
    public function a_user_can_remove_a_vendor_from_an_event()
    {
        $this->assertEquals($this->event->vendors()->count(), 1);

        $this->makeRequest($this->event->id, $this->vendor->id)
            ->assertStatus(200);

        $this->assertEquals($this->event->fresh()->vendors()->count(), 0);
    }

    /** @test */
    public function users_cannot_remove_vendors_from_events_in_other_accounts()
    {
        $eventInAnotherAccount = create('App\Event');
        $vendor = create('App\Vendor');
        $eventInAnotherAccount->vendors()->attach($vendor);

        $this->makeRequest($eventInAnotherAccount->id, $vendor->id)
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_remove_vendors_that_are_not_attached_to_the_event()
    {
        $unattachedVendor = create('App\Vendor', ['account_id' => $this->user->account->id]);

        $this->makeRequest($this->event->id, $unattachedVendor->id)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_remove_vendors_from_an_event_that_doesnt_exist()
    {
        $badEventId = 123;

        $this->makeRequest($badEventId, $this->vendor->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_remove_vendors_from_events()
    {
        $this->makeRequest($this->event->id, $this->vendor->id, false)
            ->assertStatus(401);
    }
}
