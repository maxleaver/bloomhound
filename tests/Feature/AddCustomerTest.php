<?php

namespace Tests\Feature;

use App\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_add_a_customer_to_their_account()
    {
    	$user = create('App\User');
    	$request = [
    		'name' => 'Some customer name'
    	];

    	$this->assertEquals(Customer::count(), 0);

    	$response = $this->json('POST', 'api/customers', $request, authAsUser($user))
    		->assertStatus(200);

    	$this->assertEquals(Customer::count(), 1);
    }
}