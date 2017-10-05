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
    	$this->signIn($this->user)
            ->patchJson($this->url($this->note->id), $this->request)
    		->assertStatus(200);

    	$this->assertEquals($this->note->fresh()->text, $this->updateText);
    }

    /** @test */
    public function users_can_only_update_notes_from_their_account()
    {
    	$otherAccountNote = create('App\Note');

    	$this->signIn($this->user)
            ->patchJson($this->url($otherAccountNote->id), $this->request)
    		->assertStatus(404);
    }

    /** @test */
    public function users_can_only_update_notes_that_exist()
    {
        $badId = 123;

        $this->signIn($this->user)
            ->patchJson($this->url($badId), $this->request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_notes()
    {
    	$this->patchJson($this->url($this->note->id), $this->request)
    		->assertStatus(401);
    }

    protected function url($id)
    {
    	return '/api/notes/' . $id;
    }
}
