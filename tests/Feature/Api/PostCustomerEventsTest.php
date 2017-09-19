<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCustomerEventsTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->customer = create('App\Customer');
        $this->user = create('App\User', ['account_id' => $this->customer->account->id]);
        $this->request = [
        	'customer_id' => $this->customer->id,
        	'date' => '2017-09-12T12:37:55.729Z',
            'name' => 'Event Name',
        ];
    }

    protected function getUrl($id)
    {
    	return 'api/customers/' . $id . '/events';
    }

    /** @test */
    public function authenticated_users_can_add_an_event_for_existing_customers()
    {
    	$this->assertEquals($this->customer->events->count(), 0);

    	$this->signIn($this->user)
            ->postJson($this->getUrl($this->customer->id), $this->request)
    		->assertStatus(200);

    	$this->assertEquals($this->customer->fresh()->events->count(), 1);
    }

    /** @test */
    public function authenticated_users_can_only_add_events_for_existing_customers_in_their_account()
    {
    	$someOtherCustomer = create('App\Customer');

    	$this->signIn($this->user)
            ->postJson($this->getUrl($someOtherCustomer->id), $this->request)
    		->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_events_for_existing_customers()
    {
    	$this->postJson($this->getUrl($this->customer->id), $this->request)
    		->assertStatus(401);
    }
}
