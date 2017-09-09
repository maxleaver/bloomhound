<?php

namespace Tests\Api\Feature;

use App\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// class CreateCustomersTest extends TestCase
// {
//     use RefreshDatabase;

//     /** @test */
//     public function a_user_can_add_a_customer_to_their_account()
//     {
//     	$user = create('App\User');

//     	$this->assertEquals(Customer::count(), 0);

//     	$this->actingAs($user)
//     		->post(route('customers.store'), ['name' => 'John Doe'])
//             ->assertStatus(302);

//     	$this->assertEquals(Customer::count(), 1);
//     }
// }