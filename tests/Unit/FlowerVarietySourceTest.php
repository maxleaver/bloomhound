<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlowerVarietySourceTest extends TestCase
{
    use RefreshDatabase;

    protected $source;

	protected function setUp()
    {
    	parent::setUp();

        $this->source = create('App\FlowerVarietySource');
    }

    /** @test */
    public function a_flower_variety_source_has_a_cost()
    {
        $this->assertNotNull($this->source->cost);
        $this->assertInternalType('float', $this->source->cost);
    }

    /** @test */
    public function a_flower_variety_source_has_stems_per_bunch()
    {
        $this->assertNotNull($this->source->stems_per_bunch);
        $this->assertInternalType('int', $this->source->stems_per_bunch);
    }

    /** @test */
    public function it_has_a_cost_per_stem()
    {
        $this->assertNotNull($this->source->cost_per_stem);
        $this->assertInternalType('float', $this->source->cost_per_stem);
        $this->assertEquals($this->source->cost_per_stem, $this->source->cost / $this->source->stems_per_bunch);
    }

    /** @test */
    public function a_flower_variety_source_belongs_to_a_vendor()
    {
        $this->assertInstanceOf('App\Vendor', $this->source->vendor);
    }

    /** @test */
    public function a_flower_variety_source_knows_if_it_is_the_best_price()
    {
        $variety = create('App\FlowerVariety');

        $highSource = create('App\FlowerVarietySource', [
            'flower_variety_id' => $variety->id,
            'cost' => 100,
            'stems_per_bunch' => 10,
        ]);

        $this->assertTrue($highSource->isBestPrice);

        $lowSource = create('App\FlowerVarietySource', [
            'flower_variety_id' => $variety->id,
            'cost' => 10,
            'stems_per_bunch' => 10,
        ]);

        $this->assertFalse($highSource->isBestPrice);
        $this->assertTrue($lowSource->isBestPrice);
    }

    /** @test */
    public function a_flower_variety_source_belongs_to_a_flower_variety()
    {
    	$this->assertInstanceOf('App\FlowerVariety', $this->source->variety);
    }

    /** @test */
    public function a_flower_variety_source_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->source->account);
    }
}
