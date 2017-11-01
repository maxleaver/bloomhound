<?php

namespace Tests\Feature\Api;

use App\FlowerLibrary;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFlowersTest extends TestCase
{
    use RefreshDatabase;

    protected $flowers;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->flowers = create('App\Flower', ['account_id' => $this->user->account->id], 5);
    }

    /** @test */
    public function an_authenticated_user_can_get_a_list_of_flowers_for_their_account()
    {
    	$flowersInAnotherAccount = create('App\Flower', [], 2);

        $this->signIn($this->user)
            ->getJson('api/flowers')
    		->assertStatus(200)
    		->assertJsonFragment([$this->flowers[0]->name])
    		->assertJsonFragment([$this->flowers[1]->name])
    		->assertJsonMissing([$flowersInAnotherAccount[0]->name])
    		->assertJsonMissing([$flowersInAnotherAccount[1]->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_flowers()
    {
        $this->getJson('api/flowers')
    		->assertStatus(401);
    }
}
