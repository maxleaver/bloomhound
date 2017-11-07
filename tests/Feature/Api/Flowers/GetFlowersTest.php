<?php

namespace Tests\Feature\Api;

use App\FlowerLibrary;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFlowersTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $flowers;

    protected function setUp()
    {
        parent::setUp();

        $this->account = create('App\Account');
        $this->flowers = create('App\Flower', [
            'account_id' => $this->account->id,
        ], 2);
    }

    /** @test */
    public function an_authenticated_user_can_get_a_list_of_flowers_for_their_account()
    {
    	$flowersInAnotherAccount = create('App\Flower', [], 2);

        $this->getFlowers()
    		->assertStatus(200)
    		->assertJsonFragment([$this->flowers[0]->name])
    		->assertJsonFragment([$this->flowers[1]->name])
    		->assertJsonMissing([$flowersInAnotherAccount[0]->name])
    		->assertJsonMissing([$flowersInAnotherAccount[1]->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_flowers()
    {
        $this->getFlowers(false)
    		->assertStatus(401);
    }

    protected function getFlowers($signIn = true)
    {
        $url = 'api/flowers';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
