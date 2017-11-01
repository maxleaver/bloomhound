<?php

namespace Tests\Api\Feature;

use App\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCustomersTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->request = ['name' => 'Some customer name'];
        $this->user = create('App\User');
        $this->url = 'api/customers';
    }

    /** @test */
    public function a_user_can_add_a_customer_to_their_account()
    {
    	$this->assertEquals(Customer::count(), 0);

    	$this->signIn($this->user)
            ->postJson($this->url, $this->request)
    		->assertStatus(200);

    	$this->assertEquals(Customer::count(), 1);
    }
}
