<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetItemTypesTest extends TestCase
{
    use RefreshDatabase;

    protected $types;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->types = create('App\ItemType', [], 3);
        $this->user = create('App\User');
        $this->url = 'api/item_types';
    }

    /** @test */
    public function a_user_can_get_item_types()
    {
        $this->signIn($this->user)
            ->getJson($this->url)
            ->assertStatus(200)
            ->assertJsonFragment([$this->types[0]->title])
    		->assertJsonFragment([$this->types[1]->title]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_item_types()
    {
        $this->getJson($this->url)
            ->assertStatus(401);
    }
}
