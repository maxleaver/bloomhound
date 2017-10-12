<?php

namespace Tests\Feature\Api;

use App\Note;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteNoteTest extends TestCase
{
    use RefreshDatabase;

    protected $notes;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->notes = create('App\Note', ['user_id' => $this->user->id], 3);
    }

    protected function makeRequest($noteId, $signIn = true)
    {
        $url = '/api/notes/' . $noteId;

        if ($signIn) {
            return $this->signIn($this->user)->deleteJson($url);
        }

        return $this->deleteJson($url);
    }

    /** @test */
    public function a_user_can_delete_a_note()
    {
    	$this->assertEquals(Note::count(), 3);

        $this->makeRequest($this->notes[0]->id)
            ->assertStatus(200);

    	$this->assertEquals(Note::count(), 2);
    }

    /** @test */
    public function a_user_can_only_delete_notes_on_their_account()
    {
    	$userOnAnotherAccount = create('App\User');
    	$noteOnAnotherAccount = create('App\Note', ['user_id' => $userOnAnotherAccount->id]);

        $this->makeRequest($noteOnAnotherAccount->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_notes()
    {
        $this->makeRequest($this->notes[0]->id, false)
            ->assertStatus(401);
    }
}
