<?php

namespace Tests\Api\Feature;

use App\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostItemsTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->request = ['name' => 'My Item'];
        $this->user = create('App\User');
        $this->url = 'api/items';
    }

    /** @test */
    public function a_user_can_add_items()
    {
        $this->assertEquals(Item::count(), 0);

        $this->signIn($this->user)
            ->postJson($this->url, $this->request)
            ->assertStatus(200);

        $this->assertEquals(Item::count(), 1);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_items()
    {
        $this->postJson($this->url, $this->request)
            ->assertStatus(401);
    }
}
