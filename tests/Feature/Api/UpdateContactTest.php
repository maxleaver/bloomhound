<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateContactTest extends TestCase
{
	use RefreshDatabase;

    protected $user;
    protected $contact;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->contact = create('App\Contact', ['account_id' => $this->user->account->id]);
    }

    protected function url($id)
    {
        return 'api/contacts/' . $id;
    }

    /** @test */
    public function users_can_update_a_contact_profile()
    {
        $first_name = 'Jane';
        $last_name = 'Doe';
        $email = 'email@test.com';
        $address = '123 Fake Street, Town, ND USA';
        $phone = '(555) 555-5555';
        $relationship = 'Mother of the bride';
        $request = compact('first_name', 'last_name', 'email', 'address', 'phone', 'relationship');

        $this->signIn($this->user)
            ->patchJson($this->url($this->contact->id), $request)
            ->assertStatus(200);

        $contact = $this->contact->fresh();
        $this->assertEquals($first_name, $contact->first_name);
        $this->assertEquals($last_name, $contact->last_name);
        $this->assertEquals($email, $contact->email);
        $this->assertEquals($address, $contact->address);
        $this->assertEquals($phone, $contact->phone);
        $this->assertEquals($relationship, $contact->relationship);
    }

    /** @test */
    public function users_can_only_update_contacts_in_their_account()
    {
        $contactInAnotherAccount = create('App\Contact');
        $request = ['first_name' => 'Julie', 'last_name' => 'Doe'];

        $this->signIn($this->user)
            ->patchJson($this->url($contactInAnotherAccount->id), $request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_update_contacts_that_exist()
    {
        $badId = 123;
        $request = ['first_name' => 'Julie', 'last_name' => 'Doe'];

        $this->signIn($this->user)
            ->patchJson($this->url($badId), $request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_contacts()
    {
        $request = ['first_name' => 'Julie', 'last_name' => 'Doe'];

        $this->patchJson($this->url($this->contact->id), $request)
            ->assertStatus(401);
    }
}
