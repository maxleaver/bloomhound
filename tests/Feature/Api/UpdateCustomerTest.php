<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCustomerTest extends TestCase
{
	use RefreshDatabase;

    protected $user;
    protected $customer;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->customer = create('App\Customer', ['account_id' => $this->user->account->id]);
    }

    protected function url($id)
    {
        return 'api/customers/' . $id;
    }

    /** @test */
    public function users_can_update_a_customer_profile()
    {
        $name = 'new name';
        $email = 'email@test.com';
        $address = '123 Fake Street, Town, ND USA';
        $phone = '(555) 555-5555';
        $request = compact('name', 'email', 'address', 'phone');

        $this->signIn($this->user)
            ->patchJson($this->url($this->customer->id), $request)
            ->assertStatus(200);

        $customer = $this->customer->fresh();
        $this->assertEquals($name, $customer->name);
        $this->assertEquals($email, $customer->email);
        $this->assertEquals($address, $customer->address);
        $this->assertEquals($phone, $customer->phone);
    }

    /** @test */
    public function users_can_only_update_customers_in_their_account()
    {
        $customerInAnotherAccount = create('App\Customer');

        $this->signIn($this->user)
            ->patchJson($this->url($customerInAnotherAccount->id), ['name' => 'a name'])
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_customers()
    {
        $this->patchJson($this->url($this->customer->id), ['name' => 'a name'])
            ->assertStatus(401);
    }
}
