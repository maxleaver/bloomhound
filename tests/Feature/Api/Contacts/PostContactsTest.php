<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostContactsTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->customer = create('App\Customer');
        $this->request = make('App\Contact', [
            'customer_id' => $this->customer->id
        ])->toArray();
    }

    /** @test */
    public function a_user_can_add_a_contact_to_a_customer()
    {
        $this->assertEquals($this->customer->contacts()->count(), 0);

        $this->createContact()
    		->assertStatus(200)
    		->assertJsonFragment([$this->request['email']]);

    	$this->assertEquals($this->customer->contacts()->count(), 1);
    }

    /** @test */
    public function users_can_only_add_contacts_to_existing_customers()
    {
        $this->request['customer_id'] = 555;
        $this->createContact()
    		->assertStatus(404);
    }

    /** @test */
    public function users_can_only_add_contacts_to_customers_on_their_account()
    {
        $this->request['customer_id'] = create('App\Customer')->id;
        $this->createContact()
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_contacts()
    {
        $this->createContact(false)
            ->assertStatus(401);
    }

    protected function createContact($signIn = true)
    {
        $url = 'api/contacts';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->customer->account->id,
            ]));
        }

        return $this->postJson($url, $this->request);
    }
}
