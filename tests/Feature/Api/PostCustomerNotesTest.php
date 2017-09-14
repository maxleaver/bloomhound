<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCustomerNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->request = ['text' => 'This is my note'];
        $this->user = create('App\User');
        $this->customer = create('App\Customer', ['account_id' => $this->user->account->id]);
    }

    /** @test */
    public function a_user_can_add_notes_to_a_customer()
    {
    	$this->assertEquals($this->customer->notes()->count(), 0);

        $this->signIn($this->user)
            ->postJson($this->getUrl($this->customer->id), $this->request)
    		->assertStatus(200);

        $this->assertEquals($this->customer->notes()->count(), 1);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_customers_assigned_to_their_account()
    {
    	$customer = create('App\Customer');

        $this->signIn($this->user)
            ->postJson($this->getUrl($customer->id), $this->request)
    		->assertStatus(404);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_customers_that_exist()
    {
        $this->signIn($this->user)
            ->postJson($this->getUrl(123), $this->request)
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticed_users_cant_post_notes()
    {
        $this->postJson($this->getUrl($this->customer->id), $this->request)
            ->assertStatus(401);
    }

    protected function getUrl($id)
    {
    	return '/api/customers/' . $id . '/notes';
    }
}
