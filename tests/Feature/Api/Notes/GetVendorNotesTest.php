<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetVendorNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $vendor;
    protected $notes;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->vendor = create('App\Vendor', ['account_id' => $this->user->account->id]);
        $this->notes = create('App\Note', [
        	'notable_id' => $this->vendor->id,
        	'notable_type' => 'App\Vendor'
        ], 3);
    }

    /** @test */
    public function a_user_can_view_notes_for_a_vendor()
    {
    	$someOtherNote = create('App\Note');

        $this->signIn($this->user)
            ->getJson($this->getUrl($this->vendor->id))
    		->assertStatus(200)
    		->assertJsonFragment([$this->notes[0]->text])
    		->assertJsonFragment([$this->notes[1]->text])
            ->assertJsonMissing([$someOtherNote->text]);
    }

    /** @test */
    public function a_user_can_only_view_notes_for_vendors_in_their_account()
    {
        $otherVendor = create('App\Vendor');
        $otherNotes = create('App\Note', ['notable_id' => $otherVendor->id, 'notable_type' => 'App\Vendor'], 3);

        $this->signIn($this->user)
            ->getJson($this->getUrl($otherVendor->id))
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_notes()
    {
        $this->getJson($this->getUrl($this->vendor->id))
            ->assertStatus(401);
    }

    protected function getUrl($id)
    {
        return '/api/vendors/' . $id . '/notes';
    }
}
