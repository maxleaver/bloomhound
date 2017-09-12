<?php

namespace Tests\Feature\Api;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetContactNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $contact;
    protected $notes;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->contact = create('App\Contact', ['account_id' => $this->user->account->id]);
        $this->notes = create('App\Note', [
            'notable_id' => $this->contact->id,
            'notable_type' => 'App\Contact'
        ], 3);
    }

    /** @test */
    public function a_user_can_view_notes_for_a_contact()
    {
    	$someOtherNote = create('App\Note');

        Passport::actingAs($this->user);
        $response = $this->json('GET', $this->getUrl($this->contact->id))
    		->assertStatus(200)
    		->assertJsonFragment([$this->notes[0]->text])
    		->assertJsonFragment([$this->notes[1]->text])
            ->assertJsonMissing([$someOtherNote->text]);
    }

    /** @test */
    public function a_user_gets_an_empty_array_if_there_are_no_notes()
    {
        $newContact = create('App\Contact', ['account_id' => $this->user->account->id]);

        Passport::actingAs($this->user);
        $response = $this->json('GET', $this->getUrl($newContact->id))
            ->assertStatus(200)
            ->assertJson([
                'data' => [],
                'status' => 'success'
            ]);
    }

    /** @test */
    public function a_user_can_only_view_notes_for_contacts_in_their_account()
    {
        $otherContact = create('App\Contact');
        $otherNotes = create('App\Note', ['notable_id' => $otherContact->id, 'notable_type' => 'App\Contact'], 3);

        Passport::actingAs($this->user);
        $response = $this->json('GET', $this->getUrl($otherContact->id))
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_notes()
    {
        $response = $this->json('GET', $this->getUrl($this->contact->id))
            ->assertStatus(401);
    }

    protected function getUrl($id)
    {
        return '/api/contacts/' . $id . '/notes';
    }
}
