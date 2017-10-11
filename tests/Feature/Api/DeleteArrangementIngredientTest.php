<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteArrangementIngredientTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangement;
    protected $ingredients;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->arrangement = create('App\Arrangement', ['account_id' => $this->user->account->id]);
        $this->ingredients = create('App\ArrangementIngredient', [
            'arrangement_id' => $this->arrangement->id
        ], 3);
    }

    protected function makeRequest($arrangementId, $ingredientId, $signIn = true)
    {
        $url = '/api/arrangements/' . $arrangementId . '/ingredients/' . $ingredientId;

        if ($signIn) {
            return $this->signIn($this->user)->deleteJson($url);
        }

        return $this->deleteJson($url);
    }

    /** @test */
    public function a_user_can_delete_an_ingredient_from_an_arrangement()
    {
    	$this->assertEquals($this->arrangement->ingredients->count(), 3);

        $this->makeRequest($this->arrangement->id, $this->ingredients[0]->id)
            ->assertStatus(200);

    	$this->assertEquals($this->arrangement->fresh()->ingredients->count(), 2);

        $this->makeRequest($this->arrangement->id, $this->ingredients[1]->id)
            ->assertStatus(200);

    	$this->assertEquals($this->arrangement->fresh()->ingredients->count(), 1);
    }

    /** @test */
    public function a_user_can_only_delete_ingredients_from_arrangements_on_their_account()
    {
    	$someOtherArrangement = create('App\Arrangement');
    	$someOtherIngredient = create('App\ArrangementIngredient', ['arrangement_id' => $someOtherArrangement->id]);

        $this->makeRequest($someOtherArrangement->id, $someOtherIngredient->id)
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_ingredients()
    {
        $this->makeRequest($this->arrangement->id, $this->ingredients[0]->id, false)
            ->assertStatus(401);
    }
}
