<?php

namespace Tests\Feature\Api;

use App\Customer;
use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostEventsTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $user;
    protected $url;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->request = [
            'name' => 'Event Name',
            'customer' => 'John Doe',
            'date' => '2017-09-12T12:37:55.729Z'
        ];
        $this->url = 'api/events';
    }

    /** @test */
    public function authenticated_users_can_add_an_event_for_a_new_customer()
    {
    	$this->assertEquals(Event::count(), 0);
        $this->assertEquals(Customer::count(), 0);

    	$this->signIn($this->user)
            ->postJson($this->url, $this->request)
    		->assertStatus(200);

    	$this->assertEquals(Event::count(), 1);
        $this->assertEquals(Customer::count(), 1);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_events_for_new_customers()
    {
    	$this->postJson($this->url, $this->request)
    		->assertStatus(401);
    }
}
