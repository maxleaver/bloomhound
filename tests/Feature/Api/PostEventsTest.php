<?php

namespace Tests\Feature\Api;

use App\Event;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostEventsTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $event;
    protected $request;
    protected $user;
    protected $url;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->customer = create('App\Customer', ['account_id' => $this->user->account->id]);
        $this->request = [
            'name' => 'Event Name',
            'date' => '2017-09-12T12:37:55.729Z'
        ];
        $this->url = 'api/events';
    }

    /** @test */
    public function an_authenticated_user_can_add_an_event()
    {
    	$this->assertEquals(Event::count(), 0);

        Passport::actingAs($this->user);
    	$response = $this->json('POST', $this->url, $this->request)
    		->assertStatus(200);

    	$this->assertEquals(Event::count(), 1);
    }

    /** @test */
    public function users_can_only_add_events_to_existing_customers()
    {
    	$this->request['customer_id'] = 666;

    	$response = $this->json('POST', $this->url, $this->request)
    		->assertStatus(401);
    }

    /** @test */
    public function users_cannot_add_events_to_customers_on_other_accounts()
    {
    	$someOtherCustomer = create('App\Customer');
    	$this->event['customer_id'] = $someOtherCustomer->id;

    	$response = $this->json('POST', $this->url, $this->request)
    		->assertStatus(401);

    }

    /** @test */
    public function unauthenticated_users_cannot_add_events()
    {
    	$response = $this->json('POST', $this->url, $this->request)
    		->assertStatus(401);
    }
}
