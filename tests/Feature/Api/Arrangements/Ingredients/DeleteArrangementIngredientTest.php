<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteArrangementIngredientTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangement;
    protected $ingredients;

    protected function setUp()
    {
        parent::setUp();

        $this->arrangement = create('App\Arrangement');
        $this->ingredients = create('App\ArrangementIngredient', [
            'arrangement_id' => $this->arrangement->id
        ], 3);
    }

    /** @test */
    public function a_user_can_delete_an_ingredient_from_an_arrangement()
    {
        $this->assertEquals($this->arrangement->ingredients->count(), 3);

        $this->deleteIngredient($this->arrangement->id, $this->ingredients[0]->id)
            ->assertStatus(200);

        $this->assertEquals($this->arrangement->fresh()->ingredients->count(), 2);

        $this->deleteIngredient($this->arrangement->id, $this->ingredients[1]->id)
            ->assertStatus(200);

        $this->assertEquals($this->arrangement->fresh()->ingredients->count(), 1);
    }

    /** @test */
    public function the_arrangement_price_is_returned_after_an_ingredient_is_deleted()
    {
        $this->deleteIngredient($this->arrangement->id, $this->ingredients[0]->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'total_price' => $this->arrangement->fresh()->total_price,
            ]);
    }

    /** @test */
    public function a_user_can_only_delete_ingredients_from_arrangements_on_their_account()
    {
        $someOtherArrangement = create('App\Arrangement');
        $someOtherIngredient = create('App\ArrangementIngredient', [
            'arrangement_id' => $someOtherArrangement->id
        ]);

        $this->deleteIngredient($someOtherArrangement->id, $someOtherIngredient->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_ingredients()
    {
        $this->deleteIngredient($this->arrangement->id, $this->ingredients[0]->id, false)
            ->assertStatus(401);
    }

    protected function deleteIngredient($arrangementId, $ingredientId, $signIn = true)
    {
        $url = '/api/arrangements/' . $arrangementId . '/ingredients/' . $ingredientId;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->arrangement->account->id
            ]));
        }

        return $this->deleteJson($url);
    }
}
