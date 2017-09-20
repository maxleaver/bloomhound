<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFlowerVarietiesTest extends TestCase
{
    use RefreshDatabase;

    protected $flower;
    protected $url;
    protected $user;
    protected $varieties;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->flower = create('App\Flower', ['account_id' => $this->user->account->id]);
        $this->varieties = create('App\FlowerVariety', ['flower_id' => $this->flower->id], 10);

        $this->url = 'api/flowers/' . $this->flower->id . '/varieties';
    }

    protected function getUrl($id)
    {
    	return 'api/flowers/' . $id . '/varieties';
    }

    /** @test */
    public function a_user_can_get_a_list_of_varieties_for_a_flower()
    {
    	$this->signIn($this->user)
            ->getJson($this->getUrl($this->flower->id))
    		->assertStatus(200)
    		->assertJsonFragment([$this->varieties[0]->name])
    		->assertJsonFragment([$this->varieties[1]->name]);
    }

    /** @test */
    public function a_user_can_only_get_varieties_for_flowers_in_their_account()
    {
    	$anotherAccount = create('App\Account');
    	$otherAccountFlower = create('App\Flower', ['account_id' => $anotherAccount->id]);
    	$otherVarieties = create('App\FlowerVariety', ['flower_id' => $otherAccountFlower->id], 10);

    	$this->signIn($this->user)
            ->getJson($this->getUrl($otherAccountFlower->id))
    		->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_get_varieties_for_flowers_that_exist()
    {
    	$clearlyInvalidFlowerId = 666;

    	$this->signIn($this->user)
            ->getJson($this->getUrl($clearlyInvalidFlowerId))
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cant_get_flower_varieties()
    {
    	$this->getJson($this->getUrl($this->flower->id))
    		->assertStatus(401);
    }
}
