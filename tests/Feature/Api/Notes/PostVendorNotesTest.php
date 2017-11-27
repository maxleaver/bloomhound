<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostVendorNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $vendor;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->vendor = create('App\Vendor');
        $this->request = ['text' => 'This is my note'];
    }

    /** @test */
    public function a_user_can_add_notes_to_a_vendor()
    {
        $this->assertEquals($this->vendor->notes()->count(), 0);

        $this->addNote($this->vendor->id)
            ->assertStatus(200);

        $this->assertEquals($this->vendor->notes()->count(), 1);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_vendors_assigned_to_their_account()
    {
        $this->addNote(create('App\Vendor')->id)
            ->assertStatus(404);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_vendors_that_exist()
    {
        $badId = 666;
        $this->addNote($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_vendors()
    {
        $this->addNote($this->vendor->id, false)
            ->assertStatus(401);
    }

    protected function addNote($id, $signIn = true)
    {
        $url = '/api/vendors/' . $id . '/notes';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->vendor->account->id,
            ]));
        }

        return $this->postJson($url, $this->request);
    }
}
