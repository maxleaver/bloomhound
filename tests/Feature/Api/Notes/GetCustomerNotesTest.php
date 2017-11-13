<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCustomerNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $notes;

    protected function setUp()
    {
        parent::setUp();

        $this->customer = create('App\Customer');
        $this->notes = create('App\Note', [
        	'notable_id' => $this->customer->id,
        	'notable_type' => 'App\Customer'
        ], 3);
    }

    /** @test */
    public function a_user_can_view_notes_for_a_customer()
    {
    	$someOtherNote = create('App\Note');

        $this->getNotes($this->customer->id)
    		->assertStatus(200)
    		->assertJsonFragment([$this->notes[0]->text])
    		->assertJsonFragment([$this->notes[1]->text])
            ->assertJsonMissing([$someOtherNote->text]);
    }

    /** @test */
    public function a_user_can_only_view_notes_for_customers_in_their_account()
    {
        $this->getNotes(create('App\Customer')->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_notes()
    {
        $this->getNotes($this->customer->id, false)
            ->assertStatus(401);
    }

    protected function getNotes($id, $signIn = true)
    {
        $url = '/api/customers/' . $id . '/notes';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->customer->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
