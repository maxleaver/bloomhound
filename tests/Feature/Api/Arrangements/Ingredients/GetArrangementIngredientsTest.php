<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetArrangementIngredientsTest extends TestCase
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
    public function a_user_can_get_a_list_of_ingredients_for_an_arrangement()
    {
    	$someOtherIngredient = create('App\ArrangementIngredient');

        $this->getIngredients($this->arrangement->id)
    		->assertStatus(200)
    		->assertJsonFragment([$this->ingredients[0]->arrangeable->name])
    		->assertJsonFragment([$this->ingredients[1]->arrangeable->name])
            ->assertJsonMissing([$someOtherIngredient->arrangeable->name]);
    }

    /** @test */
    public function a_user_can_only_get_ingredients_for_arrangements_in_their_account()
    {
    	$arrangementInAnotherAccount = create('App\Arrangement')->id;
        $this->getIngredients($arrangementInAnotherAccount)
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_a_list_of_ingredients()
    {
    	$this->getIngredients($this->arrangement->id, false)
    		->assertStatus(401);
    }

    protected function getIngredients($id, $signIn = true)
    {
        $url = 'api/arrangements/' . $id . '/ingredients';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->arrangement->account->id
            ]));
        }

        return $this->getJson($url);
    }
}
