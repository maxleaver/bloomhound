<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetVendorNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $vendor;
    protected $notes;

    protected function setUp()
    {
        parent::setUp();

        $this->vendor = create('App\Vendor');
        $this->notes = create('App\Note', [
        	'notable_id' => $this->vendor->id,
        	'notable_type' => 'App\Vendor'
        ], 3);
    }

    /** @test */
    public function a_user_can_view_notes_for_a_vendor()
    {
    	$someOtherNote = create('App\Note');

        $this->getNotes($this->vendor->id)
    		->assertStatus(200)
    		->assertJsonFragment([$this->notes[0]->text])
    		->assertJsonFragment([$this->notes[1]->text])
            ->assertJsonMissing([$someOtherNote->text]);
    }

    /** @test */
    public function a_user_can_only_view_notes_for_vendors_in_their_account()
    {
        $this->getNotes(create('App\Vendor')->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_notes()
    {
        $this->getNotes($this->vendor->id, false)
            ->assertStatus(401);
    }

    protected function getNotes($id, $signIn = true)
    {
        $url = '/api/vendors/' . $id . '/notes';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->vendor->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
