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

        $this->user = create('App\User');
        $this->url = 'api/items';
        $this->request = [
            'name' => 'My Item',
            'description' => 'Some description',
            'inventory' => 10,
            'arrangeable_type_id' => create('App\ArrangeableType')->id,
        ];
    }

    /** @test */
    public function a_user_can_add_items()
    {
        $this->withoutExceptionHandling();

        $this->assertEquals(Item::count(), 0);

        $this->signIn($this->user)
            ->postJson($this->url, $this->request)
            ->assertStatus(200);

        $this->assertEquals(Item::count(), 1);
    }

    /** @test */
    public function a_user_cannot_add_items_with_an_invalid_type()
    {
        $this->request['arrangeable_type_id'] = 123;

        $this->signIn($this->user)
            ->postJson($this->url, $this->request)
            ->assertStatus(422);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_items()
    {
        $this->postJson($this->url, $this->request)
            ->assertStatus(401);
    }
}
