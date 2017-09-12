<?php

namespace Tests\Api\Feature;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCustomersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_get_a_list_of_customers_on_their_account()
    {
        $user = create('App\User');
        $accountCustomers = create('App\Customer', ['account_id' => $user->account->id], 3);
        $notAccountCustomers = create('App\Customer', [], 3);

        Passport::actingAs($user);
        $response = $this->json('GET', 'api/customers')
    		->assertStatus(200)
    		->assertJsonFragment([$accountCustomers[0]->name])
    		->assertJsonFragment([$accountCustomers[1]->name])
    		->assertJsonFragment([$accountCustomers[2]->name])
            ->assertJsonMissing([$notAccountCustomers[0]->name]);
    }

	/** @test */
    public function a_user_can_get_a_specific_customer()
    {
    	$user = create('App\User');
    	$customers = create('App\Customer', ['account_id' => $user->account->id], 3);

        Passport::actingAs($user);
    	$response = $this->json('GET', 'api/customers/' . $customers[0]->id)
    		->assertStatus(200)
    		->assertJsonFragment([$customers[0]->name])
            ->assertJsonMissing([$customers[1]->name]);
    }

    /** @test */
    public function a_user_can_only_get_customers_on_their_account()
    {
    	$user = create('App\User');
    	$accountCustomers = create('App\Customer', ['account_id' => $user->account->id], 3);
    	$notAccountCustomer = create('App\Customer');

        Passport::actingAs($user);
    	$response = $this->json('GET', 'api/customers/' . $notAccountCustomer->id, [])
    		->assertStatus(404);
    }
}