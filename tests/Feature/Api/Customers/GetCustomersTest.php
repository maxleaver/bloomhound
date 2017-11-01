<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCustomersTest extends TestCase
{
    use RefreshDatabase;

    protected $customers;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->customers = create('App\Customer', ['account_id' => $this->user->account->id], 3);
    }

    /** @test */
    public function a_user_can_get_a_list_of_customers_on_their_account()
    {
        $notAccountCustomers = create('App\Customer', [], 3);

        $this->signIn($this->user)
            ->getJson('api/customers')
    		->assertStatus(200)
    		->assertJsonFragment([$this->customers[0]->name])
    		->assertJsonFragment([$this->customers[1]->name])
    		->assertJsonFragment([$this->customers[2]->name])
            ->assertJsonMissing([$notAccountCustomers[0]->name]);
    }

	/** @test */
    public function a_user_can_get_a_specific_customer()
    {
        $this->signIn($this->user)
    	   ->getJson('api/customers/' . $this->customers[0]->id)
    		->assertStatus(200)
    		->assertJsonFragment([$this->customers[0]->name])
            ->assertJsonMissing([$this->customers[1]->name]);
    }

    /** @test */
    public function a_user_can_only_get_customers_on_their_account()
    {
    	$notAccountCustomer = create('App\Customer');

        $this->signIn($this->user)
            ->getJson('api/customers/' . $notAccountCustomer->id, [])
            ->assertStatus(404);
    }
}
