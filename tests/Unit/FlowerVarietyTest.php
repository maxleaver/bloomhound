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
    public function a_flower_variety_has_an_arrangeable_type()
    {
        $this->assertNotNull($this->variety->arrangeable_type);
        $this->assertEquals($this->variety->arrangeable_type, 'flowervariety');
    }

    /** @test */
    public function a_flower_variety_may_have_a_source()
    {
        create('App\FlowerVarietySource', [
            'flower_variety_id' => $this->variety->id,
            'vendor_id' => create('App\Vendor'),
            'cost' => 5.0,
            'stems_per_bunch' => 10,
        ]);

        $this->assertInstanceOf('App\FlowerVarietySource', $this->variety->sources->first());
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
