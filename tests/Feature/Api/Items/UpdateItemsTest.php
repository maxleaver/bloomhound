<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateItemsTest extends TestCase
{
    use RefreshDatabase;

    protected $item;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->item = create('App\Item', ['account_id' => $this->user->account->id]);

        $name = 'test name';
        $description = 'test description';
        $inventory = 10;
        $cost = 10;
        $arrangeable_type_id = \App\ArrangeableType::whereName('consummable')->first()->id;
        $markup_id = \App\Markup::whereName('cost_plus_percent')->first()->id;
        $markup_value = 50;
        $use_default_markup = false;
        $this->request = compact(
            'name',
            'description',
            'inventory',
            'cost',
            'arrangeable_type_id',
            'markup_id',
            'markup_value',
            'use_default_markup'
        );
    }

    protected function makeRequest($itemId, $signIn = true)
    {
        $url = '/api/items/' . $itemId;

        if ($signIn) {
            return $this->signIn($this->user)->patchJson($url, $this->request);
        }

        return $this->patchJson($url, $this->request);
    }

    /** @test */
    public function a_user_can_update_an_item()
    {
        $this->makeRequest($this->item->id)
            ->assertStatus(200);

        $item = $this->item->fresh();
        $this->assertEquals($this->request['name'], $item->name);
        $this->assertEquals($this->request['description'], $item->description);
        $this->assertEquals($this->request['inventory'], $item->inventory);
        $this->assertEquals($this->request['cost'], $item->cost);
        $this->assertEquals($this->request['arrangeable_type_id'], $item->type->id);
        $this->assertEquals($this->request['markup_id'], $item->markup->id);
        $this->assertEquals($this->request['markup_value'], $item->markup_value);
        $this->assertFalse($item->use_default_markup);
    }

    /** @test */
    public function a_user_cannot_update_an_item_with_an_invalid_type()
    {
        $invalidTypeId = 123;
        $this->request['arrangeable_type_id'] = $invalidTypeId;
        $this->makeRequest($this->item->id)
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_cannot_update_an_item_with_an_invalid_markup_id()
    {
        $invalidMarkupId = 123;
        $this->request['markup_id'] = $invalidMarkupId;
        $this->makeRequest($this->item->id)
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_can_only_update_an_item_in_their_account()
    {
        $itemInAnotherAccount = create('App\Item');
        $this->makeRequest($itemInAnotherAccount->id)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_update_items_that_exist()
    {
        $invalidItemId = 123;
        $this->makeRequest($invalidItemId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_items()
    {
        $this->makeRequest($this->item->id, false)
            ->assertStatus(401);
    }
}
