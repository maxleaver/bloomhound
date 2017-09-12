<?php

namespace Tests\Feature\Api;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostContactNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $contact;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->request = ['text' => 'This is my note'];
        $this->user = create('App\User');
        $this->contact = create('App\Contact', ['account_id' => $this->user->account->id]);
    }

    /** @test */
    public function a_user_can_add_notes_to_a_contact()
    {
        $this->assertEquals($this->contact->notes()->count(), 0);

        Passport::actingAs($this->user);
        $response = $this->json('POST', $this->getUrl($this->contact->id), $this->request)
    		->assertStatus(200);

        $this->assertEquals($this->contact->notes()->count(), 1);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_contacts_assigned_to_their_account()
    {
        $contact = create('App\Contact');

        Passport::actingAs($this->user);
        $response = $this->json('POST', $this->getUrl($contact->id), $this->request)
    		->assertStatus(404);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_contacts_that_exist()
    {
        Passport::actingAs($this->user);
        $response = $this->json('POST', $this->getUrl(123), $this->request)
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticed_users_cant_post_notes()
    {
        $response = $this->json('POST', $this->getUrl($this->contact->id), $this->request)
            ->assertStatus(401);
    }

    protected function getUrl($id)
    {
        return '/api/contacts/' . $id . '/notes';
    }
}
