<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetContactsTest extends TestCase
{
    use RefreshDatabase;

    protected $contacts;
    protected $customer;

    protected function setUp()
    {
        parent::setUp();

        $this->customer = create('App\Customer');
        $this->contacts = create('App\Contact', [
            'account_id' => $this->customer->account->id,
            'customer_id' => $this->customer->id
        ], 3);
    }

    /** @test */
    public function a_user_can_get_a_list_of_contacts_on_their_account()
    {
        $someOtherContact = create('App\Contact');

        $this->getAccountContacts()
            ->assertStatus(200)
            ->assertJsonFragment([$this->contacts[0]->email])
            ->assertJsonFragment([$this->contacts[1]->email])
            ->assertJsonMissing([$someOtherContact->email]);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_get_contacts()
    {
        $this->getAccountContacts(false)
            ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_get_a_list_of_contacts_for_a_specific_customer()
    {
        $otherContact = create('App\Contact', [
            'account_id' => $this->customer->account->id,
            'customer_id' => create('App\Customer', [
                'account_id' => $this->customer->account->id
            ])->id
        ]);

        $this->getCustomerContacts($this->customer->id)
            ->assertStatus(200)
            ->assertJsonFragment([$this->contacts[0]->email])
            ->assertJsonFragment([$this->contacts[1]->email])
            ->assertJsonMissing([$otherContact->email]);
    }

    /** @test */
    public function a_user_can_only_get_contacts_for_customers_in_their_account()
    {
        $anotherCustomer = create('App\Customer');
        $otherContacts = create('App\Contact', [
            'account_id' => $anotherCustomer->account->id,
            'customer_id' => $anotherCustomer->id,
        ]);

        $this->getCustomerContacts($anotherCustomer->id)
            ->assertStatus(404);
    }

    /** @test */
    public function a_user_can_get_a_specific_contact()
    {
        $this->getContact($this->contacts[0]->id)
            ->assertStatus(200)
            ->assertJsonFragment([$this->contacts[0]->name]);
    }

    /** @test */
    public function users_cannot_get_specific_contacts_in_other_accounts()
    {
        $otherContact = create('App\Contact')->id;
        $this->getContact($otherContact, false)
            ->assertStatus(401);
    }

    protected function getAccountContacts($signIn = true)
    {
        $url = 'api/contacts';

        $this->authenticate($signIn);

        return $this->getJson($url);
    }

    protected function getCustomerContacts($id, $signIn = true)
    {
        $url = 'api/customers/' . $id . '/contacts';

        $this->authenticate($signIn);

        return $this->getJson($url);
    }

    protected function getContact($id, $signIn = true)
    {
        $url = 'api/contacts/' . $id;

        $this->authenticate($signIn);

        return $this->getJson($url);
    }

    protected function authenticate($signIn)
    {
        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->customer->account->id,
            ]));
        }
    }
}
