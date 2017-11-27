<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCustomerTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->customer = create('App\Customer');
        $this->request = make('App\Customer')->toArray();
    }

    /** @test */
    public function users_can_update_a_customer_profile()
    {
        $this->updateCustomer($this->customer->id)
            ->assertStatus(200);

        $customer = $this->customer->fresh();
        $this->assertEquals($this->request['name'], $customer->name);
        $this->assertEquals($this->request['email'], $customer->email);
        $this->assertEquals($this->request['address'], $customer->address);
        $this->assertEquals($this->request['phone'], $customer->phone);
    }

    /** @test */
    public function users_can_only_update_customers_in_their_account()
    {
        $customerInAnotherAccount = create('App\Customer')->id;
        $this->updateCustomer($customerInAnotherAccount)
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_only_update_customers_that_exist()
    {
        $badId = 123;
        $this->updateCustomer($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_customers()
    {
        $this->updateCustomer($this->customer->id, false, true)
            ->assertStatus(401);
    }

    protected function updateCustomer($id, $signIn = true, $withJson = false)
    {
        $url = 'api/customers/' . $id;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->customer->account->id
            ]));
        }

        if ($withJson) {
            return $this->patchJson($url, $this->request);
        }

        return $this->patch($url, $this->request);
    }
}
