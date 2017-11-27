<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetContactNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $contact;
    protected $notes;

    protected function setUp()
    {
        parent::setUp();

        $this->contact = create('App\Contact');
        $this->notes = create('App\Note', [
            'notable_id' => $this->contact->id,
            'notable_type' => 'App\Contact'
        ], 3);
    }

    /** @test */
    public function a_user_can_view_notes_for_a_contact()
    {
        $someOtherNote = create('App\Note');

        $this->getNotes($this->contact->id)
            ->assertStatus(200)
            ->assertJsonFragment([$this->notes[0]->text])
            ->assertJsonFragment([$this->notes[1]->text])
            ->assertJsonMissing([$someOtherNote->text]);
    }

    /** @test */
    public function a_user_gets_an_empty_array_if_there_are_no_notes()
    {
        $newContact = create('App\Contact', [
            'account_id' => $this->contact->account->id
        ])->id;

        $this->getNotes($newContact)
            ->assertStatus(200)
            ->assertJson([]);
    }

    /** @test */
    public function a_user_can_only_view_notes_for_contacts_in_their_account()
    {
        $this->getNotes(create('App\Contact')->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_notes()
    {
        $this->getNotes($this->contact->id, false)
            ->assertStatus(401);
    }

    protected function getNotes($id, $signIn = true)
    {
        $url = '/api/contacts/' . $id . '/notes';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->contact->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
