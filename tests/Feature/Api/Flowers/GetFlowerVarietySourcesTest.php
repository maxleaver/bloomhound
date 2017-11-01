<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFlowerVarietySourcesTest extends TestCase
{
    use RefreshDatabase;

    protected $sources;
    protected $user;
    protected $variety;
    protected $vendor;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->variety = create('App\FlowerVariety', [
        	'flower_id' => create('App\Flower', ['account_id' => $this->user->account->id])->id
        ]);
        $this->vendor = create('App\Vendor', ['account_id' => $this->user->account->id]);
        $this->sources = create('App\FlowerVarietySource', [
        	'account_id' => $this->user->account->id,
        	'flower_variety_id' => $this->variety->id,
        	'vendor_id' => $this->vendor->id,
        ], 10);
    }

    protected function getUrl($id)
    {
    	return 'api/varieties/' . $id . '/sources';
    }

    /** @test */
    public function users_can_get_a_list_of_sources_for_a_flower_variety()
    {
    	$anotherVariety = create('App\FlowerVariety');
    	$otherSources = create('App\FlowerVarietySource', [
    		'account_id' => $this->user->account->id,
        	'flower_variety_id' => create('App\FlowerVariety')->id,
        	'vendor_id' => $this->vendor->id,
    	], 10);

    	$this->signIn($this->user)
            ->getJson($this->getUrl($this->variety->id))
    		->assertStatus(200)
    		->assertJsonFragment([$this->sources[0]->cost])
    		->assertJsonFragment([$this->sources[1]->cost])
    		->assertJsonMissing([$otherSources[0]->cost])
    		->assertJsonMissing([$otherSources[0]->cost]);
    }

    /** @test */
    public function users_can_only_get_sources_for_flower_varieties_in_their_account()
    {
    	$someOtherVariety = create('App\FlowerVariety');
    	create('App\FlowerVarietySource', [
        	'flower_variety_id' => $someOtherVariety->id,
    	], 10);

    	$this->signIn($this->user)
            ->getJson($this->getUrl($someOtherVariety->id))
    		->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_flower_varieties()
    {
    	$this->getJson($this->getUrl($this->variety->id))
    		->assertStatus(401);
    }
}
