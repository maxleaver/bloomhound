<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateContactTest extends TestCase
{
	use RefreshDatabase;

    protected $contact;

    protected function setUp()
    {
        parent::setUp();

        $this->contact = create('App\Contact');
        $this->request = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'email@test.com',
            'address' => '123 Fake Street, Town, ND USA',
            'phone' => '(555) 555-5555',
            'relationship' => 'Mother of the bride',
        ];
    }

    /** @test */
    public function users_can_update_a_contact_profile()
    {
        $this->updateContact($this->contact->id)
            ->assertStatus(200);

        $contact = $this->contact->fresh();
        $this->assertEquals($this->request['first_name'], $contact->first_name);
        $this->assertEquals($this->request['last_name'], $contact->last_name);
        $this->assertEquals($this->request['email'], $contact->email);
        $this->assertEquals($this->request['address'], $contact->address);
        $this->assertEquals($this->request['phone'], $contact->phone);
        $this->assertEquals($this->request['relationship'], $contact->relationship);
    }

    /** @test */
    public function users_can_only_update_contacts_in_their_account()
    {
        $contactInAnotherAccount = create('App\Contact')->id;
        $this->updateContact($contactInAnotherAccount)
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_only_update_contacts_that_exist()
    {
        $badId = 123;
        $this->updateContact($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_contacts()
    {
        $this->updateContact($this->contact->id, false)
            ->assertStatus(401);
    }

    protected function updateContact($id, $signIn = true)
    {
        $url = 'api/contacts/' . $id;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->contact->account->id,
            ]));
        }

        return $this->patchJson($url, $this->request);
    }
}
