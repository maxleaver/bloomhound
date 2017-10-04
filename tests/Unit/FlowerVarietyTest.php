<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlowerVarietyTest extends TestCase
{
    use RefreshDatabase;

    protected $variety;

	protected function setUp()
    {
    	parent::setUp();

        $this->variety = create('App\FlowerVariety', ['account_id' => 1]);
    }

    /** @test */
    public function a_flower_variety_has_a_name()
    {
        $this->assertNotNull($this->variety->name);
    }

    /** @test */
    public function a_flower_variety_has_an_ingredient_name()
    {
        $this->assertNotNull($this->variety->ingredient_name);
        $this->assertEquals($this->variety->ingredient_name, $this->variety->flower->name . ' - ' . $this->variety->name);
    }

    /** @test */
    public function a_flower_variety_has_an_arrangeable_type()
    {
        $this->assertNotNull($this->variety->arrangeable_type);
        $this->assertEquals($this->variety->arrangeable_type, 'flowervariety');
    }

    /** @test */
    public function a_flower_variety_may_have_sources()
    {
        create('App\FlowerVarietySource', [
            'flower_variety_id' => $this->variety->id,
            'vendor_id' => create('App\Vendor'),
            'cost' => 5.0,
            'stems_per_bunch' => 10,
        ], 10);

        $this->assertInstanceOf('App\FlowerVarietySource', $this->variety->sources->first());
    }

    /** @test */
    public function a_flower_variety_can_have_a_best_source()
    {
        create('App\FlowerVarietySource', [
            'flower_variety_id' => $this->variety->id,
            'vendor_id' => create('App\Vendor'),
            'cost' => 5.0,
            'stems_per_bunch' => 10,
        ], 10);

        $this->assertInstanceOf('App\FlowerVarietySource', $this->variety->fresh()->best_source);
    }

    /** @test */
    public function it_knows_which_of_its_sources_has_the_best_price()
    {
        $sources = create('App\FlowerVarietySource', [
            'flower_variety_id' => $this->variety->id,
        ], 10);
        $bestPrice = $sources->sortBy('cost_per_stem')->first();

        $this->assertEquals($this->variety->fresh()->best_price_id, $bestPrice->id);
    }

    /** @test */
    public function it_updates_the_best_price_if_a_source_is_deleted()
    {
        $bestPrice = create('App\FlowerVarietySource', [
            'flower_variety_id' => $this->variety->id,
            'cost' => 10,
            'stems_per_bunch' => 10,
        ]);

        $nextBestPrice = create('App\FlowerVarietySource', [
            'flower_variety_id' => $this->variety->id,
            'cost' => 100,
            'stems_per_bunch' => 10,
        ]);

        $this->assertEquals($this->variety->fresh()->best_price_id, $bestPrice->id);

        $bestPrice->delete();

        $this->assertEquals($this->variety->fresh()->best_price_id, $nextBestPrice->id);
    }

    /** @test */
    public function a_flower_variety_belongs_to_a_flower()
    {
    	$this->assertInstanceOf('App\Flower', $this->variety->flower);
    }

    /** @test */
    public function a_flower_variety_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->variety->account);
    }

    /** @test */
    public function an_item_can_be_used_as_an_arrangement_ingredient()
    {
        create('App\ArrangementIngredient', [
            'arrangeable_id' => $this->variety->id,
            'arrangeable_type' => 'App\FlowerVariety',
        ]);

        $this->assertInstanceOf('App\ArrangementIngredient', $this->variety->used->first());
    }
}
