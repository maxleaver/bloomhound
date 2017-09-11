<?php

namespace Tests\Feature\Api;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetContactsTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function a_user_can_get_a_list_of_contacts_on_their_account()
    {
    	$user = create('App\User');

    	$contacts = create('App\Contact', ['account_id' => $user->account->id], 3);
    	$someOtherContact = create('App\Contact');

    	Passport::actingAs($user, ['api/contacts']);

        $response = $this->json('GET', 'api/contacts')
    		->assertStatus(200)
    		->assertJsonFragment([$contacts[0]->email])
    		->assertJsonFragment([$contacts[1]->email])
    		->assertJsonFragment([$contacts[2]->email])
            ->assertJsonMissing([$someOtherContact->email]);
    }

    /** @test */
    public function a_user_can_get_a_list_of_contacts_for_a_specific_customer()
    {
    	$this->withoutExceptionHandling();

    	$user = create('App\User');

    	$customer = create('App\Customer', ['account_id' => $user->account->id]);
    	$anotherCustomer = create('App\Customer', ['account_id' => $user->account->id]);

    	$contacts = create('App\Contact', [
    		'account_id' => $user->account->id,
    		'customer_id' => $customer->id
    	], 3);
    	$otherContact = create('App\Contact', [
    		'account_id' => $user->account->id,
    		'customer_id' => $anotherCustomer->id
    	]);

    	$url = 'api/customers/' . $customer->id . '/contacts';

    	Passport::actingAs($user, [$url]);

        $response = $this->json('GET', $url)
    		->assertStatus(200)
    		->assertJsonFragment([$contacts[0]->email])
    		->assertJsonFragment([$contacts[1]->email])
    		->assertJsonFragment([$contacts[2]->email])
            ->assertJsonMissing([$otherContact->email]);
    }

    /** @test */
    public function an_unauthenticed_user_cannot_get_contacts()
    {
    	$contacts = create('App\Contact', [], 3);

    	$response = $this->json('GET', 'api/contacts')
    		->assertStatus(401);
    }
}
