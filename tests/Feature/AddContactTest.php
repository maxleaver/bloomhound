<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddContactTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $customer;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->customer = create('App\Customer', ['account_id' => $this->user->account->id]);

        $this->request = [
    		'first_name' => 'John',
    		'last_name' => 'Doe',
    		'email' => 'john@doe.com',
    		'phone' => '5556667788',
    		'address' => '',
    		'relationship' => 'some string',
    		'customer_id' => $this->customer->id
    	];
    }

    /** @test */
    public function a_user_can_add_a_contact_to_a_customer()
    {
    	$this->assertEquals($this->customer->contacts()->count(), 0);

    	$response = $this->json('POST', 'api/contacts', $this->request, authAsUser($this->user))
    		->assertStatus(200)
    		->assertJsonFragment(['john@doe.com']);

    	$this->assertEquals($this->customer->contacts()->count(), 1);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_contacts()
    {
    	$response = $this->json('POST', 'api/contacts', $this->request)
    		->assertStatus(401);
    }

    /** @test */
    public function users_cannot_add_contacts_to_customers_in_other_accounts()
    {
    	$this->request['customer_id'] = 555;

    	$response = $this->json('POST', 'api/contacts', $this->request, authAsUser($this->user))
    		->assertStatus(404);
    }
}