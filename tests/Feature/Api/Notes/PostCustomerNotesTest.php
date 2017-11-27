<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCustomerNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->customer = create('App\Customer');
        $this->request = ['text' => 'This is my note'];
    }

    /** @test */
    public function a_user_can_add_notes_to_a_customer()
    {
        $this->assertEquals($this->customer->notes()->count(), 0);

        $this->addNote($this->customer->id)
            ->assertStatus(200);

        $this->assertEquals($this->customer->notes()->count(), 1);
    }

    /** @test */
    public function users_cannot_add_notes_to_customers_in_other_accounts()
    {
        $this->addNote(create('App\Customer')->id)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_add_notes_to_customers_that_dont_exist()
    {
        $badId = 666;
        $this->addNote($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_post_notes()
    {
        $this->addNote($this->customer->id, false)
            ->assertStatus(401);
    }

    protected function addNote($id, $signIn = true)
    {
        $url = '/api/customers/' . $id . '/notes';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->customer->account->id,
            ]));
        }

        return $this->postJson($url, $this->request);
    }
}
