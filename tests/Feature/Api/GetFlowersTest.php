<?php

namespace Tests\Feature\Api;

use App\FlowerLibrary;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFlowersTest extends TestCase
{
    use RefreshDatabase;

    protected $customFlowers;
    protected $defaultFlowers;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $defaultLib = FlowerLibrary::whereType('default')->first();
		$customLib = FlowerLibrary::whereType('custom')->first();

        $this->user = create('App\User');
        $this->defaultFlowers = create('App\Flower', [
        	'flower_library_id' => $defaultLib->id
        ], 10);
        $this->customFlowers = create('App\Flower', [
        	'account_id' => $this->user->account->id,
    		'flower_library_id' => $customLib->id,
    	], 10);
    }

	/** @test */
    public function an_authenticated_user_can_get_flowers_from_the_default_library()
    {
    	$this->signIn($this->user)
            ->getJson('api/flowers?lib=default')
    		->assertStatus(200)
    		->assertJsonFragment([$this->defaultFlowers[0]->name])
    		->assertJsonFragment([$this->defaultFlowers[1]->name])
    		->assertJsonMissing([$this->customFlowers[0]->name])
    		->assertJsonMissing([$this->customFlowers[1]->name]);
    }

    /** @test */
    public function an_authenticated_user_can_get_flowers_that_have_been_added_to_their_account()
    {
        $this->signIn($this->user)
            ->getJson('api/flowers?lib=custom')
    		->assertStatus(200)
    		->assertJsonFragment([$this->customFlowers[0]->name])
    		->assertJsonFragment([$this->customFlowers[1]->name])
    		->assertJsonMissing([$this->defaultFlowers[0]->name])
    		->assertJsonMissing([$this->defaultFlowers[1]->name]);
    }

    /** @test */
    public function attempting_to_get_flowers_from_a_library_that_doesnt_exist_results_in_error()
    {
        $this->signIn($this->user)
            ->getJson('api/flowers?lib=bad-library-name')
    		->assertStatus(404);
    }

    /** @test */
    public function an_authenticated_user_can_get_all_flowers()
    {
    	$this->signIn($this->user)
            ->getJson('api/flowers')
    		->assertStatus(200)
    		->assertJsonFragment([$this->defaultFlowers[0]->name])
    		->assertJsonFragment([$this->defaultFlowers[1]->name])
    		->assertJsonFragment([$this->customFlowers[0]->name])
    		->assertJsonFragment([$this->customFlowers[1]->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_flowers()
    {
        $this->getJson('api/flowers')
    		->assertStatus(401);
    }
}
