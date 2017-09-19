<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCustomerEventsTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $events;
    protected $otherEvents;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->customer = create('App\Customer', ['account_id' => $this->user->account->id]);
        $this->events = create('App\Event', [
        	'account_id' => $this->user->account->id,
        	'customer_id' => $this->customer->id,
        ], 3);
        $this->otherEvents = create('App\Event', ['account_id' => $this->user->account->id], 3);
    }

    protected function getUrl($id)
    {
    	return 'api/customers/' . $id . '/events';
    }

    /** @test */
    public function an_authenticated_user_can_get_a_customers_events()
    {
    	$this->signIn($this->user)
            ->getJson($this->getUrl($this->customer->id))
    		->assertStatus(200)
    		->assertJsonFragment([$this->events[0]->name])
    		->assertJsonFragment([$this->events[1]->name])
    		->assertJsonFragment([$this->events[2]->name])
            ->assertJsonMissing([$this->otherEvents[0]->name]);
    }

    /** @test */
    public function an_authenticated_user_cannot_get_events_for_customers_in_other_accounts()
    {
    	$customerInAnotherAccount = create('App\Customer');
    	$otherEvents = create('App\Event', [
    		'account_id' => $customerInAnotherAccount->account->id,
    		'customer_id' => $customerInAnotherAccount->id
    	]);

    	$this->signIn($this->user)
            ->getJson($this->getUrl($customerInAnotherAccount->id))
    		->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_customers_events()
    {
    	$this->getJson($this->getUrl($this->customer->id))
    		->assertStatus(401);
    }
}
