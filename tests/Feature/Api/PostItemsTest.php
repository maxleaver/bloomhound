<?php

namespace Tests\Api\Feature;

use App\ArrangeableType;
use App\ArrangeableTypeSetting;
use App\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostItemsTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $type;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->url = 'api/items';
        $this->type = ArrangeableType::whereName('consummable')->first();
        $this->request = [
            'arrangeable_type_id' => $this->type->id,
            'name' => 'My Item',
            'description' => 'Some description',
            'inventory' => 10,
            'cost' => 9.95,
        ];
    }

    /** @test */
    public function a_user_can_add_items()
    {
        $this->assertEquals(Item::count(), 0);

        $this->signIn($this->user)
            ->postJson($this->url, $this->request)
            ->assertStatus(200);

        $item = Item::whereName($this->request['name'])->first();

        $this->assertNotNull($item);
        $this->assertInstanceOf('App\ArrangeableType', $item->type);
        $this->assertInstanceOf('App\Markup', $item->markup);
        $this->assertEquals($item->name, $this->request['name']);
        $this->assertEquals($item->description, $this->request['description']);
        $this->assertEquals($item->inventory, $this->request['inventory']);
        $this->assertEquals($item->cost, $this->request['cost']);
    }

    /** @test */
    public function when_a_user_creates_an_item_the_default_markup_for_its_type_is_set()
    {
        $defaultSetting = ArrangeableTypeSetting::whereAccountId($this->user->account->id)
            ->whereArrangeableTypeId($this->type->id)
            ->first();
        $defaultSetting->markup_value = 10;
        $defaultSetting->save();

        $this->signIn($this->user)
            ->postJson($this->url, $this->request)
            ->assertStatus(200);

        $item = Item::whereName($this->request['name'])->first();

        $this->assertInstanceOf('App\Markup', $item->markup);
        $this->assertEquals($item->markup->id, $defaultSetting->markup->id);
        $this->assertEquals($item->markup_value, $defaultSetting->markup_value);
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
