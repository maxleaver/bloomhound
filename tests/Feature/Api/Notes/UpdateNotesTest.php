<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $note;
    protected $request;
    protected $updateText;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->note = create('App\Note', ['user_id' => $this->user->id]);
        $this->updateText = 'I have been updated.';
        $this->request = ['text' => $this->updateText];
    }

    /** @test */
    public function a_user_can_update_a_note()
    {
        $this->updateNote($this->note->id)
            ->assertStatus(200);

        $this->assertEquals($this->note->fresh()->text, $this->updateText);
    }

    /** @test */
    public function users_can_only_update_notes_from_their_account()
    {
        $otherNote = create('App\Note')->id;
        $this->updateNote($otherNote)
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_only_update_notes_that_exist()
    {
        $badId = 123;
        $this->updateNote($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_notes()
    {
        $this->updateNote($this->note->id, false)
            ->assertStatus(401);
    }

    protected function updateNote($id, $signIn = true)
    {
        $url = '/api/notes/' . $id;

        if ($signIn) {
            $this->signIn($this->user);
        }

        return $this->patchJson($url, $this->request);
    }
}
