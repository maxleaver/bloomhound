<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCustomerEventsTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->customer = create('App\Customer');
        $this->request = [
            'customer_id' => $this->customer->id,
            'date' => Carbon::now()->addWeek(2)->toRfc3339String(),
            'name' => 'Event Name',
        ];
    }

    /** @test */
    public function users_can_add_an_event_for_a_customer()
    {
        $this->assertEquals($this->customer->events->count(), 0);

        $this->createEvent($this->customer->id)
            ->assertStatus(200);

        $this->assertEquals($this->customer->fresh()->events->count(), 1);
    }

    /** @test */
    public function an_event_requires_a_name()
    {
        $this->request['name'] = null;
        $this->createEvent($this->customer->id, true, false)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function an_event_requires_a_date()
    {
        $this->request['date'] = null;
        $this->createEvent($this->customer->id, true, false)
            ->assertSessionHasErrors('date');
    }

    /** @test */
    public function an_event_date_must_be_in_the_future()
    {
        $this->request['date'] = Carbon::now()->subWeek(2)->toRfc3339String();
        $this->createEvent($this->customer->id, true, false)
            ->assertSessionHasErrors('date');
    }

    /** @test */
    public function users_cannot_add_events_to_other_accounts()
    {
        $someOtherCustomer = create('App\Customer')->id;
        $this->createEvent($someOtherCustomer)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_events_for_customers()
    {
        $this->createEvent($this->customer->id, false)
            ->assertStatus(401);
    }

    protected function createEvent($id, $signIn = true, $withJson = true)
    {
        $url = 'api/customers/' . $id . '/events';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->customer->account->id,
            ]));
        }

        if ($withJson) {
            return $this->postJson($url, $this->request);
        }

        return $this->post($url, $this->request);
    }
}
