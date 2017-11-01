<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetEventVendorsTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $user;
    protected $url;
    protected $vendors;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->vendors = create('App\Vendor', ['account_id' => $this->user->account->id], 5);
        $this->event = create('App\Event', ['account_id' => $this->user->account->id]);
        $this->event->vendors()->attach($this->vendors);
    }

    protected function url($id)
    {
        return 'api/events/' . $id . '/vendors';
    }

    /** @test */
    public function a_user_can_get_a_list_of_vendors_assigned_to_an_event()
    {
        $unassignedVendor = create('App\Vendor', ['account_id' => $this->user->account->id]);
        $inAnotherAccount = create('App\Vendor');

        $this->signIn($this->user)
            ->getJson($this->url($this->event->id))
    		->assertStatus(200)
    		->assertJsonFragment([$this->vendors[0]->name])
    		->assertJsonFragment([$this->vendors[1]->name])
    		->assertJsonFragment([$this->vendors[2]->name])
            ->assertJsonMissing([$unassignedVendor->name])
            ->assertJsonMissing([$inAnotherAccount->name]);
    }

    /** @test */
    public function a_user_cannot_get_vendors_for_events_in_other_accounts()
    {
        $eventInAnotherAccount = create('App\Event');

        $this->signIn($this->user)
            ->getJson($this->url($eventInAnotherAccount->id))
            ->assertStatus(403);
    }

    /** @test */
    public function unauthorized_users_cannot_get_vendors_for_an_event()
    {
    	$this->getJson($this->url($this->event->id))
    		->assertStatus(401);
    }
}
