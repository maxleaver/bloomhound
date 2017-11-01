<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArrangementIngredientTest extends TestCase
{
	use RefreshDatabase;

    protected $arrangement;
    protected $ingredient;

    protected function setUp()
    {
        parent::setUp();

        $this->arrangement = create('App\Arrangement');
        $this->ingredient = create('App\ArrangementIngredient', [
            'arrangement_id' => $this->arrangement->id,
            'quantity' => 2,
        ]);
    }

    /** @test */
    public function a_user_can_update_the_ingredient_quantity_in_an_arrangement()
    {
        $ingredient = $this->arrangement->ingredients->first();

        $this->assertNotNull($ingredient);
        $this->assertEquals($ingredient->quantity, 2);

        $this->updateIngredient($this->arrangement->id, $this->ingredient->id)
            ->assertStatus(200);

        $this->assertEquals($ingredient->fresh()->quantity, 10);
    }

    /** @test */
    public function the_arrangement_price_is_returned_after_an_ingredient_is_updated()
    {
        $this->updateIngredient($this->arrangement->id, $this->ingredient->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'total_price' => $this->arrangement->fresh()->total_price,
            ]);
    }

    /** @test */
    public function an_ingredient_requires_a_quantity()
    {
        $request = [];
        $this->updateIngredient($this->arrangement->id, $this->ingredient->id, $request)
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function an_ingredient_requires_a_quantity_greater_than_zero()
    {
        $request = ['quantity' => 0];
        $this->updateIngredient($this->arrangement->id, $this->ingredient->id, $request)
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function users_cannot_update_ingredients_for_arrangements_in_other_accounts()
    {
        $someOtherArrangement = create('App\Arrangement');
        $this->updateIngredient($someOtherArrangement->id, $this->ingredient->id)
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_update_ingredients_that_arent_in_a_valid_arrangement()
    {
        $badId = 666;
        $this->updateIngredient($badId, $this->ingredient->id)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_update_invalid_ingredients()
    {
        $badId = 666;
        $this->updateIngredient($this->arrangement->id, $badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_ingredients()
    {
        $this->patchJson($this->url($this->arrangement->id, $this->ingredient->id), [
                'quantity' => 10
            ])
            ->assertStatus(401);
    }

    protected function url($arrangementId, $ingredientId)
    {
        return 'api/arrangements/' . $arrangementId . '/ingredients/' . $ingredientId;
    }

    protected function updateIngredient($arrangementId, $ingredientId, $request = null)
    {
        if (is_null($request)) {
            $request = ['quantity' => 10];
        }

        $this->signIn(create('App\User', [
            'account_id' => $this->arrangement->account->id,
        ]));

        return $this->patch($this->url($arrangementId, $ingredientId), $request);
    }
}
