<?php

namespace Tests\Api\Feature;

use App\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCustomersTest extends TestCase
{
    use RefreshDatabase;

    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->request = ['name' => 'Some customer name'];
    }

    /** @test */
    public function a_user_can_add_a_customer_to_their_account()
    {
    	$this->assertEquals(Customer::count(), 0);

        $this->createCustomer()
    		->assertStatus(200);

    	$this->assertEquals(Customer::count(), 1);
    }

    /** @test */
    public function a_customer_requires_a_name()
    {
        $this->request['name'] = null;
        $this->createCustomer()
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function unauthenticated_users_cannot_create_customers()
    {
        $this->createCustomer(false, true)
            ->assertStatus(401);
    }

    protected function createCustomer($signIn = true, $withJson = false)
    {
        $url = 'api/customers';

        if ($signIn) {
            $this->signIn(create('App\User'));
        }

        if ($withJson) {
            return $this->postJson($url, $this->request);
        }

        return $this->post($url, $this->request);
    }
}
