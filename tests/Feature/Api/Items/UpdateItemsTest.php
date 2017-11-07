<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateItemsTest extends TestCase
{
    use RefreshDatabase;

    protected $item;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->item = create('App\Item');
        $this->request = create('App\Item', [
            'account_id' => $this->item->account->id,
        ])->toArray();
    }

    /** @test */
    public function a_user_can_update_an_item()
    {
        $this->updateItem($this->item->id)
            ->assertStatus(200);

        $item = $this->item->fresh();
        $this->assertEquals($this->request['name'], $item->name);
        $this->assertEquals($this->request['description'], $item->description);
        $this->assertEquals($this->request['inventory'], $item->inventory);
        $this->assertEquals($this->request['cost'], $item->cost);
        $this->assertEquals($this->request['arrangeable_type_id'], $item->type->id);
        $this->assertEquals($this->request['markup_id'], $item->markup->id);
        $this->assertEquals($this->request['markup_value'], $item->markup_value);
        $this->assertEquals($this->request['use_default_markup'], $item->use_default_markup);
    }

    /** @test */
    public function a_user_cannot_update_an_item_with_an_invalid_type()
    {
        $invalidTypeId = 123;
        $this->request['arrangeable_type_id'] = $invalidTypeId;

        $this->updateItem($this->item->id)
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_cannot_update_an_item_with_an_invalid_markup_id()
    {
        $invalidMarkupId = 123;
        $this->request['markup_id'] = $invalidMarkupId;

        $this->updateItem($this->item->id)
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_can_only_update_an_item_in_their_account()
    {
        $itemInAnotherAccount = create('App\Item');

        $this->updateItem($itemInAnotherAccount->id)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_update_items_that_exist()
    {
        $invalidItemId = 123;

        $this->updateItem($invalidItemId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_items()
    {
        $this->updateItem($this->item->id, false)
            ->assertStatus(401);
    }

    protected function updateItem($id, $signIn = true)
    {
        $url = '/api/items/' . $id;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->item->account->id,
            ]));
        }

        return $this->patchJson($url, $this->request);
    }
}
