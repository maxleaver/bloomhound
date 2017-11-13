<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetItemsTest extends TestCase
{
    use RefreshDatabase;

    protected $items;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->items = create('App\Item', [
            'account_id' => $this->user->account->id,
        ], 10);
    }

    /** @test */
    public function a_user_can_get_a_list_of_items()
    {
        $otherItems = create('App\Item', [], 10);

        $this->getItems()
            ->assertStatus(200)
            ->assertJsonFragment([$this->items[0]->name])
    		->assertJsonFragment([$this->items[1]->name])
    		->assertJsonMissing([$otherItems[0]->name])
    		->assertJsonMissing([$otherItems[1]->name]);
    }

    /** @test */
    public function a_user_can_get_a_specific_item()
    {
        $this->getItems($this->items[0]->id)
            ->assertStatus(200)
            ->assertJsonFragment([$this->items[0]->name])
            ->assertJsonMissing([$this->items[1]->name]);
    }

    /** @test */
    public function a_user_can_only_get_an_item_in_their_account()
    {
        $someOtherItem = create('App\Item')->id;

        $this->getItems($someOtherItem)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_items()
    {
        $this->getItems(null, false)
            ->assertStatus(401);
    }

    protected function getItems($id = null, $signIn = true)
    {
        $url = isset($id) ? 'api/items/' . $id : 'api/items';

        if ($signIn) {
            $this->signIn($this->user);
        }

        return $this->getJson($url);
    }
}
