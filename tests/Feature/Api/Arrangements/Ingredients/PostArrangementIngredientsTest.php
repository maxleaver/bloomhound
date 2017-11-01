<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostArrangementIngredientsTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangement;

    protected function setUp()
    {
        parent::setUp();

        $this->arrangement = create('App\Arrangement');
    }

    /** @test */
    public function a_user_can_add_ingredients_to_an_arrangement()
    {
        $this->assertEquals($this->arrangement->ingredients()->count(), 0);

        $this->addIngredient($this->arrangement->id, $this->makeRequest())
            ->assertStatus(200);

        $this->assertEquals($this->arrangement->ingredients()->count(), 2);
    }

    /** @test */
    public function a_user_can_add_multiple_of_the_same_ingredient()
    {
        $item = create('App\Item', [
            'account_id' => $this->arrangement->account->id
        ]);

        $request = [
            [
                'id' => $item->id,
                'type' => $item->arrangeableType,
                'quantity' => 10,
            ],
            [
                'id' => $item->id,
                'type' => $item->arrangeableType,
                'quantity' => 7,
            ],
        ];

        $this->assertEquals($this->arrangement->ingredients()->count(), 0);

        $this->addIngredient($this->arrangement->id, $request)
            ->assertStatus(200);

        $this->assertEquals($this->arrangement->ingredients()->count(), 2);
        $this->assertEquals($this->arrangement
            ->ingredients()
            ->where('arrangeable_id', $item->id)
            ->sum('quantity'), 17);
    }

    /** @test */
    public function an_ingredient_requires_a_quantity_greater_than_zero()
    {
        $item = create('App\Item');
        $request = [
            [
                'id' => $item->id,
                'type' => $item->arrangeableType,
            ]
        ];
        $this->addIngredient($this->arrangement->id, $request)
            ->assertSessionHasErrors('0.quantity');

        $request['quantity'] = 0;
        $this->addIngredient($this->arrangement->id, $request)
            ->assertSessionHasErrors('0.quantity');
    }

    /** @test */
    public function the_arrangement_price_is_returned_after_ingredients_are_added()
    {
        $this->addIngredient($this->arrangement->id, $this->makeRequest())
            ->assertStatus(200)
            ->assertJsonFragment([
                'total_price' => $this->arrangement->fresh()->total_price,
            ]);
    }

    /** @test */
    public function users_cannot_add_ingredients_to_arrangements_in_other_accounts()
    {
    	$someOtherArrangement = create('App\Arrangement')->id;

        $this->addIngredient($someOtherArrangement, $this->makeRequest())
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_add_arrangeable_ingredients_to_arrangements()
    {
    	$badRequest = [
    		[
    			'id' => create('App\User')->id,
        		'type' => 'user',
        		'quantity' => 5,
    		]
    	];

        $this->addIngredient($this->arrangement->id, $badRequest)
            ->assertSessionHasErrors('0.type');
    }

    /** @test */
    public function users_cannot_add_ingredients_from_other_accounts_to_arrangements()
    {
    	$badRequest = [
    		[
    			'id' => create('App\Item')->id,
    			'type' => 'item',
    			'quantity' => 5,
    		]
    	];

        $this->addIngredient($this->arrangement->id, $badRequest)
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_add_existing_ingredients_to_arrangements()
    {
        $clearlyInvalidId = 123;
    	$badRequest = [
    		[
    			'id' => $clearlyInvalidId,
    			'type' => 'item',
    			'quantity' => 5,
    		]
    	];

        $this->addIngredient($this->arrangement->id, $badRequest)
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_ingredients_to_arrangements()
    {
        $this->postJson($this->url($this->arrangement->id), $this->makeRequest())
    		->assertStatus(401);
    }

    protected function makeRequest()
    {
        $overrides = [
            'account_id' => $this->arrangement->account->id,
            'use_default_markup' => false,
        ];

        $item = create('App\Item', $overrides);
        $flowerVariety = create('App\FlowerVariety', $overrides);
        return [
            [
                'id' => $item->id,
                'type' => 'item',
                'quantity' => 5,
            ],
            [
                'id' => $flowerVariety->id,
                'type' => 'flowervariety',
                'quantity' => 10,
            ],
        ];
    }

    protected function url($id)
    {
        return 'api/arrangements/' . $id . '/ingredients';
    }

    protected function addIngredient($id, $request, $signIn = true)
    {
        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->arrangement->account->id,
            ]));
        }

        return $this->post($this->url($id), $request);
    }
}
