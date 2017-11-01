<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostVendorsTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $user;
    protected $url;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->url = 'api/vendors';
        $this->request = ['name' => 'Vendor Name'];
    }

    /** @test */
    public function a_user_can_add_a_vendor()
    {
        $this->assertEquals($this->user->account->vendors()->count(), 0);

    	$this->signIn($this->user)
            ->postJson($this->url, $this->request)
    		->assertStatus(200)
    		->assertJsonFragment([$this->request['name']]);

    	$this->assertEquals($this->user->account->vendors()->count(), 1);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_vendors()
    {
    	$this->postJson($this->url, $this->request)
    		->assertStatus(401);
    }
}
