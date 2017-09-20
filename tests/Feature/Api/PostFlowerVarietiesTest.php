<?php

namespace Tests\Feature\Api;

use App\FlowerVariety;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostFlowerVarietiesTest extends TestCase
{
    use RefreshDatabase;

    protected $flower;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->flower = create('App\Flower', ['account_id' => $this->user->account->id]);
        $this->request = ['name' => 'Some Variety'];
    }

    protected function getUrl($id)
    {
    	return 'api/flowers/' . $id . '/varieties';
    }

    /** @test */
    public function users_can_add_varieties_to_a_flower()
    {
    	$this->assertEquals(FlowerVariety::count(), 1);

    	$this->signIn($this->user)
            ->postJson($this->getUrl($this->flower->id), $this->request)
    		->assertStatus(200);

    	$this->assertEquals(FlowerVariety::count(), 2);
    }

    /** @test */
    public function users_can_only_add_varieties_to_flowers_in_their_account()
    {
    	$someOtherFlower = create('App\Flower');

    	$this->signIn($this->user)
            ->postJson($this->getUrl($someOtherFlower->id), $this->request)
    		->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_varieties()
    {
    	$this->postJson($this->getUrl($this->flower->id), $this->request)
    		->assertStatus(401);
    }
}
