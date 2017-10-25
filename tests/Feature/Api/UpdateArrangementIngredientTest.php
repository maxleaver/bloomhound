<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArrangementIngredientTest extends TestCase
{
	use RefreshDatabase;

    protected $arrangement;
    protected $ingredient;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->arrangement = create('App\Arrangement');
        $this->ingredient = create('App\ArrangementIngredient', [
            'arrangement_id' => $this->arrangement->id,
            'quantity' => 2,
        ]);
        $this->user = create('App\User', [
            'account_id' => $this->arrangement->account->id,
        ]);
    }

    protected function url($arrangementId, $ingredientId)
    {
        return 'api/arrangements/' . $arrangementId . '/ingredients/' . $ingredientId;
    }

    /** @test */
    public function a_user_can_update_the_ingredient_quantity_in_an_arrangement()
    {
        $request = ['quantity' => 10];

        $ingredient = $this->arrangement->ingredients->first();

        $this->assertNotNull($ingredient);
        $this->assertEquals($ingredient->quantity, 2);

        $this->signIn($this->user)
            ->patchJson(
                $this->url($this->arrangement->id, $this->ingredient->id),
                $request)
            ->assertStatus(200);

        $this->assertEquals($ingredient->fresh()->quantity, 10);
    }

    /** @test */
    public function users_cannot_update_ingredients_for_arrangements_in_other_accounts()
    {
        $someOtherArrangement = create('App\Arrangement');
        $request = ['quantity' => 10];

        $this->signIn($this->user)
            ->patchJson(
                $this->url($someOtherArrangement->id, $this->ingredient->id),
                $request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_update_ingredients_that_arent_in_a_valid_arrangement()
    {
        $badArrangementId = 666;
        $request = ['quantity' => 10];

        $this->signIn($this->user)
            ->patchJson($this->url($badArrangementId, $this->ingredient->id), $request)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_update_invalid_ingredients()
    {
        $badIngredientId = 666;
        $request = ['quantity' => 10];

        $this->signIn($this->user)
            ->patchJson(
                $this->url($this->arrangement->id, $badIngredientId),
                $request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_ingredients()
    {
        $request = ['quantity' => 10];

        $this->patchJson(
                $this->url($this->arrangement->id, $this->ingredient->id),
                $request)
            ->assertStatus(401);
    }
}
