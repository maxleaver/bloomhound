<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetArrangementIngredientsTest extends TestCase
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

    protected function getUrl($id)
    {
    	return 'api/arrangements/' . $id . '/ingredients';
    }

    /** @test */
    public function a_user_can_get_a_list_of_ingredients_for_an_arrangement()
    {
    	$someOtherIngredient = create('App\ArrangementIngredient');

        $this->signIn($this->user)
            ->getJson($this->getUrl($this->arrangement->id))
    		->assertStatus(200)
    		->assertJsonFragment([$this->ingredients[0]->arrangeable->name])
    		->assertJsonFragment([$this->ingredients[1]->arrangeable->name])
            ->assertJsonMissing([$someOtherIngredient->arrangeable->name]);
    }

    /** @test */
    public function a_user_can_only_get_ingredients_for_arrangements_in_their_account()
    {
    	$arrangementInAnotherAccount = create('App\Arrangement');

    	$this->signIn($this->user)
            ->getJson($this->getUrl($arrangementInAnotherAccount->id))
    		->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_a_list_of_ingredients()
    {
    	$this->getJson($this->getUrl($this->arrangement->id))
    		->assertStatus(401);
    }
}
