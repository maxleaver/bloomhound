<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCustomerNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $notes;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->customer = create('App\Customer', ['account_id' => $this->user->account->id]);
        $this->notes = create('App\Note', [
        	'notable_id' => $this->customer->id,
        	'notable_type' => 'App\Customer'
        ], 3);
    }

    /** @test */
    public function a_user_can_view_notes_for_a_customer()
    {
    	$someOtherNote = create('App\Note');

        $this->signIn($this->user)
            ->getJson($this->getUrl($this->customer->id))
    		->assertStatus(200)
    		->assertJsonFragment([$this->notes[0]->text])
    		->assertJsonFragment([$this->notes[1]->text])
            ->assertJsonMissing([$someOtherNote->text]);
    }

    /** @test */
    public function a_user_can_only_view_notes_for_customers_in_their_account()
    {
        $otherCustomer = create('App\Customer');
        $otherNotes = create('App\Note', ['notable_id' => $otherCustomer->id, 'notable_type' => 'App\Customer'], 3);

        $this->signIn($this->user)
            ->getJson($this->getUrl($otherCustomer->id))
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_notes()
    {
        $this->getJson($this->getUrl($this->customer->id))
            ->assertStatus(401);
    }

    protected function getUrl($id)
    {
        return '/api/customers/' . $id . '/notes';
    }
}
