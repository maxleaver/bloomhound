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

    /** @test */
    public function a_user_can_delete_a_note()
    {
        $this->assertEquals(Note::count(), 3);

        $this->deleteNote($this->notes[0]->id)
            ->assertStatus(200);

        $this->assertEquals(Note::count(), 2);
    }

    /** @test */
    public function a_user_can_only_delete_notes_on_their_account()
    {
        $noteOnAnotherAccount = create('App\Note', [
            'user_id' => create('App\User')->id
        ]);

        $this->deleteNote($noteOnAnotherAccount->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_notes()
    {
        $this->deleteNote($this->notes[0]->id, false)
            ->assertStatus(401);
    }

    protected function deleteNote($id, $signIn = true)
    {
        $url = '/api/notes/' . $id;

        if ($signIn) {
            return $this->signIn($this->user)->deleteJson($url);
        }

        return $this->deleteJson($url);
    }
}
