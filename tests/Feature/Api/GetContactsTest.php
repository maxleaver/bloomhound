<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetContactsTest extends TestCase
{
	use RefreshDatabase;

    protected $contacts;
    protected $customer;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->customer = create('App\Customer', ['account_id' => $this->user->account->id]);
        $this->contacts = create('App\Contact', [
            'account_id' => $this->user->account->id,
            'customer_id' => $this->customer->id
        ], 3);
    }

    /** @test */
    public function a_user_can_get_a_list_of_contacts_on_their_account()
    {
    	$someOtherContact = create('App\Contact');

        $this->signIn($this->user)
            ->getJson('api/contacts')
    		->assertStatus(200)
    		->assertJsonFragment([$this->contacts[0]->email])
    		->assertJsonFragment([$this->contacts[1]->email])
            ->assertJsonMissing([$someOtherContact->email]);
    }

    /** @test */
    public function a_user_can_get_a_list_of_contacts_for_a_specific_customer()
    {
    	$anotherCustomer = create('App\Customer', ['account_id' => $this->user->account->id]);
    	$otherContact = create('App\Contact', [
    		'account_id' => $this->user->account->id,
    		'customer_id' => $anotherCustomer->id
    	]);

    	$url = 'api/customers/' . $this->customer->id . '/contacts';

    	$this->signIn($this->user)
            ->getJson($url)
    		->assertStatus(200)
    		->assertJsonFragment([$this->contacts[0]->email])
    		->assertJsonFragment([$this->contacts[1]->email])
            ->assertJsonMissing([$otherContact->email]);
    }

    /** @test */
    public function a_user_can_get_a_specific_contact()
    {
        $this->signIn($this->user)
            ->getJson('api/contacts/' . $this->contacts[0]->id)
            ->assertStatus(200)
            ->assertJsonFragment([$this->contacts[0]->name]);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_get_contacts()
    {
    	$this->getJson('api/contacts')
    		->assertStatus(401);
    }
}
