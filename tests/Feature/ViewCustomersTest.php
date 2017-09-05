<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCustomersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_get_a_list_of_customers_on_their_account()
    {
        $user = create('App\User');
        $accountCustomers = create('App\Customer', ['account_id' => $user->account->id], 3);
        $notAccountCustomers = create('App\Customer', [], 3);

        $response = $this->json('GET', 'api/customers', [], authAsUser($user))
    		->assertStatus(200)
    		->assertJsonFragment([$accountCustomers[0]->name])
    		->assertJsonFragment([$accountCustomers[1]->name])
    		->assertJsonFragment([$accountCustomers[2]->name])
            ->assertJsonMissing([$notAccountCustomers[0]->name]);
    }

	/** @test */
    public function a_user_can_view_a_specific_customer()
    {
    	$user = create('App\User');
    	$customers = create('App\Customer', ['account_id' => $user->account->id], 3);
    	$response = $this->json('GET', 'api/customers/' . $customers[0]->id, [], authAsUser($user))
    		->assertStatus(200)
    		->assertJsonFragment([$customers[0]->name])
            ->assertJsonMissing([$customers[1]->name]);
    }

    /** @test */
    public function a_user_can_only_view_a_customer_on_their_account()
    {
    	$user = create('App\User');
    	$accountCustomers = create('App\Customer', ['account_id' => $user->account->id], 3);
    	$notAccountCustomer = create('App\Customer');

    	$response = $this->json('GET', 'api/customers/' . $notAccountCustomer->id, [], authAsUser($user))
    		->assertStatus(404);
    }
}