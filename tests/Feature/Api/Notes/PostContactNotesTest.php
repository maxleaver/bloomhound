<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostContactNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $contact;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->contact = create('App\Contact');
        $this->request = ['text' => 'This is my note'];
    }

    /** @test */
    public function a_user_can_add_notes_to_a_contact()
    {
        $this->assertEquals($this->contact->notes()->count(), 0);

        $this->addNote($this->contact->id)
    		->assertStatus(200);

        $this->assertEquals($this->contact->notes()->count(), 1);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_contacts_assigned_to_their_account()
    {
        $this->addNote(create('App\Contact')->id)
    		->assertStatus(404);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_contacts_that_exist()
    {
        $badId = 666;
        $this->addNote($badId)
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticed_users_cant_post_notes()
    {
        $this->addNote($this->contact->id, false)
            ->assertStatus(401);
    }

    protected function addNote($id, $signIn = true)
    {
        $url = '/api/contacts/' . $id . '/notes';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->contact->account->id,
            ]));
        }

        return $this->postJson($url, $this->request);
    }
}
