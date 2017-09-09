<?php

namespace Tests\Api\Feature;

use App\Customer;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCustomersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_add_a_customer_to_their_account()
    {
    	$user = create('App\User');
    	$request = [
    		'name' => 'Some customer name'
    	];

        Passport::actingAs($user, ['api/customers']);

    	$this->assertEquals(Customer::count(), 0);

    	$response = $this->json('POST', 'api/customers', $request)
    		->assertStatus(200);

    	$this->assertEquals(Customer::count(), 1);
    }
}