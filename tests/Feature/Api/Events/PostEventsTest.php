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

    protected function setUp()
    {
        parent::setUp();

        $this->request = [
            'name' => 'Event Name',
            'customer' => 'John Doe',
            'date' => '2017-09-12T12:37:55.729Z'
        ];
    }

    /** @test */
    public function a_user_can_create_an_event_for_a_new_customer()
    {
        $this->assertEquals(Event::count(), 0);
        $this->assertEquals(Customer::count(), 0);

        $this->createEvent()
            ->assertStatus(200);

        $this->assertEquals(Event::count(), 1);
        $this->assertEquals(Customer::count(), 1);
    }

    /** @test */
    public function an_event_requires_a_name()
    {
        $this->request['name'] = null;

        $this->createEvent()
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function an_event_requires_a_customer_name_or_id()
    {
        $this->request['customer'] = null;

        $this->createEvent()
            ->assertSessionHasErrors('customer');
    }

    /** @test */
    public function an_event_requires_a_valid_date()
    {
        $this->request['date'] = null;

        $this->createEvent()
            ->assertSessionHasErrors('date');
    }

    /** @test */
    public function unauthenticated_users_cannot_create_events()
    {
        $this->createEvent(false, true)
            ->assertStatus(401);
    }

    protected function createEvent($signIn = true, $withJson = false)
    {
        $url = '/api/events';

        if ($signIn) {
            $this->signIn(create('App\User'));
        }

        if ($withJson) {
            return $this->postJson($url, $this->request);
        }

        return $this->post($url, $this->request);
    }
}
