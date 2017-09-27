<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetItemsTest extends TestCase
{
    use RefreshDatabase;

    protected $items;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->items = create('App\Item', ['account_id' => $this->user->account->id], 10);
        $this->url = 'api/items';
    }

    /** @test */
    public function a_user_can_get_items_in_their_account()
    {
        $otherItems = create('App\Item', [], 10);

        $this->signIn($this->user)
            ->getJson($this->url)
            ->assertStatus(200)
            ->assertJsonFragment([$this->items[0]->name])
    		->assertJsonFragment([$this->items[1]->name])
    		->assertJsonMissing([$otherItems[0]->name])
    		->assertJsonMissing([$otherItems[1]->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_items()
    {
        $this->getJson($this->url)
            ->assertStatus(401);
    }
}
