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

        $this->signIn($this->user)
            ->deleteJson($this->url($this->notes[0]->id))
    		->assertStatus(200);

    	$this->assertEquals(Note::count(), 2);
    }

    /** @test */
    public function a_user_can_only_delete_notes_on_their_account()
    {
    	$userOnAnotherAccount = create('App\User');
    	$noteOnAnotherAccount = create('App\Note', ['user_id' => $userOnAnotherAccount->id]);

    	$this->signIn($this->user)
            ->deleteJson($this->url($noteOnAnotherAccount->id))
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_notes()
    {
        $this->deleteJson($this->url($this->notes[0]->id))
    		->assertStatus(401);
    }

    protected function url($id)
    {
    	return '/api/notes/' . $id;
    }
}
