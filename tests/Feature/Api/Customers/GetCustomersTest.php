<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCustomersTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $customers;

    protected function setUp()
    {
        parent::setUp();

        $this->account = create('App\Account');
        $this->customers = create('App\Customer', [
            'account_id' => $this->account->id
        ], 3);
    }

    /** @test */
    public function a_user_can_get_a_list_of_customers_on_their_account()
    {
        $otherCustomers = create('App\Customer', [], 3);

        $this->getCustomerList()
            ->assertStatus(200)
            ->assertJsonFragment([$this->customers[0]->name])
            ->assertJsonFragment([$this->customers[1]->name])
            ->assertJsonFragment([$this->customers[2]->name])
            ->assertJsonMissing([$otherCustomers[0]->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_a_list_of_customers()
    {
        $this->getCustomerList(false)
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_get_a_specific_customer()
    {
        $this->getCustomer($this->customers[0]->id)
            ->assertStatus(200)
            ->assertJsonFragment([$this->customers[0]->name])
            ->assertJsonMissing([$this->customers[1]->name]);
    }

    /** @test */
    public function users_cannot_get_a_customer_in_another_account()
    {
        $customerInOtherAccount = create('App\Customer')->id;

        $this->getCustomer($customerInOtherAccount)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_a_customer()
    {
        $this->getCustomer($this->customers[0]->id, false)
            ->assertStatus(401);
    }

    protected function getCustomerList($signIn = true)
    {
        $url = 'api/customers';

        $this->authenticate($signIn);

        return $this->getJson($url);
    }

    protected function getCustomer($id, $signIn = true)
    {
        $url = 'api/customers/' . $id;

        $this->authenticate($signIn);

        return $this->getJson($url);
    }

    protected function authenticate($signIn)
    {
        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->account->id,
            ]));
        }
    }
}
