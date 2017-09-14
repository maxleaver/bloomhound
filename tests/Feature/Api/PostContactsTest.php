<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostContactsTest extends TestCase
{
    use RefreshDatabase;

    protected $contact;
    protected $customer;
    protected $request;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->customer = create('App\Customer', ['account_id' => $this->user->account->id]);
        $this->contact = make('App\Contact', ['customer_id' => $this->customer->id]);
        $this->url = 'api/contacts';
    }

    /** @test */
    public function a_user_can_add_a_contact_to_a_customer()
    {
        $this->assertEquals($this->customer->contacts()->count(), 0);

    	$this->signIn($this->user)
            ->postJson($this->url, $this->contact->toArray())
    		->assertStatus(200)
    		->assertJsonFragment([$this->contact->email]);

    	$this->assertEquals($this->customer->contacts()->count(), 1);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_contacts()
    {
    	$this->postJson($this->url, $this->contact->toArray())
    		->assertStatus(401);
    }

    /** @test */
    public function users_can_only_add_contacts_to_existing_customers()
    {
        $this->contact->customer_id = 555;

    	$this->signIn($this->user)
            ->postJson($this->url, $this->contact->toArray())
    		->assertStatus(404);
    }

    /** @test */
    public function users_can_only_add_contacts_to_customers_on_their_account()
    {
        $someOtherCustomer = create('App\Customer');
        $this->contact->customer_id = $someOtherCustomer->id;

        $this->signIn($this->user)
            ->postJson($this->url, $this->contact->toArray())
            ->assertStatus(403);
    }
}
