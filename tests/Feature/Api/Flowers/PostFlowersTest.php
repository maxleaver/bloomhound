<?php

namespace Tests\Feature\Api;

use App\Flower;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostFlowersTest extends TestCase
{
    use RefreshDatabase;

    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->request = ['name' => 'Some Flower'];
    }

    /** @test */
    public function authenticated_users_can_create_flowers()
    {
    	$this->assertEquals(Flower::count(), 0);

        $this->createFlower($this->request)
    		->assertStatus(200);

    	$this->assertEquals(Flower::count(), 1);
    }

    /** @test */
    public function unauthenticated_users_cannot_create_flowers()
    {
    	$this->createFlower($this->request, false)
    		->assertStatus(401);
    }

    protected function createFlower($request, $signIn = true)
    {
        $url = 'api/flowers';

        if ($signIn) {
            $this->signIn(create('App\User'));
        }

        return $this->postJson($url, $request);
    }
}
