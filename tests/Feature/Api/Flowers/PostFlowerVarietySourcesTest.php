<?php

namespace Tests\Feature\Api;

use App\FlowerVarietySource;
use App\Vendor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostFlowerVarietySourcesTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $variety;
    protected $vendor;

    protected function setUp()
    {
        parent::setUp();

        $this->variety = create('App\FlowerVariety');

        $this->vendor = create('App\Vendor', [
            'account_id' => $this->variety->flower->account->id
        ]);

        $this->request = [
            make('App\FlowerVarietySource', [
                'vendor_id' => $this->vendor->id
            ])->toArray()
        ];
    }

    /** @test */
    public function users_can_add_a_source_to_a_flower_variety()
    {
    	$this->assertEquals(FlowerVarietySource::count(), 0);

        $this->createSources($this->variety->id, $this->request)
    		->assertStatus(200);

    	$this->assertEquals(FlowerVarietySource::count(), 1);
    }

    /** @test */
    public function users_can_add_multiple_sources_to_a_flower_variety()
    {
        $request = [
			[
				'vendor_name' => 'A new vendor',
	        	'cost' => 9.99,
	        	'stems_per_bunch' => 10,
    		],
    		make('App\FlowerVarietySource', [
                'vendor_id' => $this->vendor->id
            ])->toArray()
    	];

    	$this->assertEquals(FlowerVarietySource::count(), 0);
    	$this->assertEquals(Vendor::count(), 1);

        $this->createSources($this->variety->id, $request)
    		->assertStatus(200);

    	$this->assertEquals(FlowerVarietySource::count(), 2);
    	$this->assertEquals(Vendor::count(), 2);
    }

    /** @test */
    public function users_can_only_add_sources_to_varieties_of_flowers_in_their_account()
    {
    	$otherVariety = create('App\FlowerVariety')->id;

        $this->createSources($otherVariety, $this->request)
    		->assertStatus(403);
    }

    /** @test */
    public function users_can_only_add_sources_to_flower_varieties_that_exist()
    {
    	$badId = 666;

        $this->createSources($badId, $this->request)
    		->assertStatus(404);
    }

    /** @test */
    public function users_can_only_add_flower_variety_sources_to_vendors_on_their_account()
    {
    	$someOtherVendor = create('App\Vendor');
    	$this->request[0]['vendor_id'] = $someOtherVendor->id;

        $this->createSources($this->variety->id, $this->request)
    		->assertStatus(403);
    }

    /** @test */
    public function providing_a_vendor_name_creates_a_new_vendor()
    {
    	$this->request[0]['vendor_id'] = null;
    	$this->request[0]['vendor_name'] = 'Vendor Name';

    	$this->assertEquals(\App\Vendor::count(), 1);

        $this->createSources($this->variety->id, $this->request)
    		->assertStatus(200);

    	$this->assertEquals(\App\Vendor::count(), 2);
    }

    /** @test */
    public function if_both_a_vendor_id_and_name_are_provided_the_vendor_id_is_used()
    {
    	$this->request[0]['vendor_name'] = 'Vendor Name';

    	$this->assertEquals(\App\Vendor::count(), 1);

        $response = $this->createSources($this->variety->id, $this->request)
    		->assertStatus(200);

    	$newRecord = FlowerVarietySource::find($response->getData()[0]->id);

    	$this->assertEquals(\App\Vendor::count(), 1);
    	$this->assertEquals($newRecord->vendor->name, $this->vendor->name);
    }

    /** @test */
    public function a_vendor_id_must_be_valid()
    {
    	$this->request[0]['vendor_id'] = 666;

        $this->createSources($this->variety->id, $this->request)
    		->assertStatus(403);
    }

    /** @test */
    public function users_must_provide_a_vendor_id_or_a_vendor_name()
    {
        $this->request[0]['vendor_id'] = null;
        $this->request[0]['vendor_name'] = null;

        $this->createSources($this->variety->id, $this->request)
            ->assertSessionHasErrors('0.vendor_id')
            ->assertSessionHasErrors('0.vendor_name');
    }

    /** @test */
    public function a_flower_variety_source_must_have_a_cost()
    {
    	$this->request[0]['cost'] = null;

        $this->createSources($this->variety->id, $this->request)
            ->assertSessionHasErrors('0.cost');
    }

    /** @test */
    public function a_flower_variety_source_must_have_stems_per_bunch()
    {
    	$this->request[0]['stems_per_bunch'] = null;

        $this->createSources($this->variety->id, $this->request)
            ->assertSessionHasErrors('0.stems_per_bunch');
    }

    /** @test */
    public function unauthenticated_users_cannot_add_sources()
    {
    	$this->createSources($this->variety->id, $this->request, false, true)
    		->assertStatus(401);
    }

    protected function createSources($id, $request, $signIn = true, $withJson = false)
    {
        $url = 'api/varieties/' . $id . '/sources';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->variety->flower->account->id
            ]));
        }

        if ($withJson) {
            return $this->postJson($url, $request);
        }

        return $this->post($url, $request);
    }
}
