<?php

namespace Tests\Feature\Api;

use App\Flower;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostFlowersTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $user;
    protected $url;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->request = ['name' => 'Some Flower'];
        $this->url = 'api/flowers';
    }

    /** @test */
    public function authenticated_users_can_create_flowers()
    {
    	$this->assertEquals(Flower::count(), 0);

    	$this->signIn($this->user)
            ->postJson($this->url, $this->request)
    		->assertStatus(200);

    	$this->assertEquals(Flower::count(), 1);
    }

    /** @test */
    public function unauthenticated_users_cannot_create_flowers()
    {
    	$this->postJson($this->url, $this->request)
    		->assertStatus(401);
    }
}
