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

    protected function setUp()
    {
        parent::setUp();

        $this->customer = create('App\Customer');
        $this->events = create('App\Event', [
            'account_id' => $this->customer->account->id,
            'customer_id' => $this->customer->id,
        ], 3);
        $this->otherEvents = create('App\Event', [
            'account_id' => $this->customer->account->id
        ], 3);
    }

    /** @test */
    public function a_user_can_get_a_list_of_events_for_a_customer()
    {
        $this->getEvents($this->customer->id)
            ->assertStatus(200)
            ->assertJsonFragment([$this->events[0]->name])
            ->assertJsonFragment([$this->events[1]->name])
            ->assertJsonFragment([$this->events[2]->name])
            ->assertJsonMissing([$this->otherEvents[0]->name]);
    }

    /** @test */
    public function an_authenticated_user_cannot_get_events_for_customers_in_other_accounts()
    {
        $inAnotherAccount = create('App\Customer');
        $otherEvents = create('App\Event', [
            'account_id' => $inAnotherAccount->account->id,
            'customer_id' => $inAnotherAccount->id
        ]);

        $this->getEvents($inAnotherAccount->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_customers_events()
    {
        $this->getEvents($this->customer->id, false)
            ->assertStatus(401);
    }

    protected function getEvents($id, $signIn = true)
    {
        $url = 'api/customers/' . $id . '/events';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->customer->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
