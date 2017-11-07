<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFlowerVarietiesTest extends TestCase
{
    use RefreshDatabase;

    protected $flower;
    protected $varieties;

    protected function setUp()
    {
        parent::setUp();

        $this->flower = create('App\Flower');
        $this->varieties = create('App\FlowerVariety', [
            'flower_id' => $this->flower->id
        ], 10);
    }

    /** @test */
    public function a_user_can_get_a_list_of_varieties_for_a_flower()
    {
    	$this->getVarieties($this->flower->id)
    		->assertStatus(200)
    		->assertJsonFragment([$this->varieties[0]->name])
    		->assertJsonFragment([$this->varieties[1]->name]);
    }

    /** @test */
    public function a_user_can_only_get_varieties_for_flowers_in_their_account()
    {
    	$anotherFlower = create('App\Flower');
    	$otherVarieties = create('App\FlowerVariety', [
            'flower_id' => $anotherFlower->id
        ], 10);

        $this->getVarieties($anotherFlower->id)
    		->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_get_varieties_for_flowers_that_exist()
    {
    	$badId = 666;

        $this->getVarieties($badId)
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cant_get_flower_varieties()
    {
    	$this->getVarieties($this->flower->id, false)
    		->assertStatus(401);
    }

    protected function getVarieties($id, $signIn = true)
    {
        $url = 'api/flowers/' . $id . '/varieties';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->flower->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
