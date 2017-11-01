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
    public function a_user_can_get_a_specific_item()
    {
        $this->signIn($this->user)
            ->getJson($this->url . '/' . $this->items[0]->id)
            ->assertStatus(200)
            ->assertJsonFragment([$this->items[0]->name])
            ->assertJsonMissing([$this->items[1]->name]);
    }

    /** @test */
    public function a_user_can_only_get_an_item_in_their_account()
    {
        $someOtherItem = create('App\Item');

        $this->signIn($this->user)
            ->getJson($this->url . '/' . $someOtherItem->id)
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_items()
    {
        $this->getJson($this->url)
            ->assertStatus(401);
    }
}
