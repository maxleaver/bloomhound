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

        $this->variety = create('App\FlowerVariety');
    }

    /** @test */
    public function it_has_a_name()
    {
        $this->assertNotNull($this->variety->name);
    }

    /** @test */
    public function it_has_an_ingredient_name()
    {
        $this->assertNotNull($this->variety->ingredient_name);
        $this->assertEquals($this->variety->ingredient_name, $this->variety->flower->name . ' - ' . $this->variety->name);
    }

    /** @test */
    public function it_has_an_arrangeable_type()
    {
        $this->assertNotNull($this->variety->arrangeable_type);
        $this->assertEquals($this->variety->arrangeable_type, 'flowervariety');
    }

    /** @test */
    public function it_has_a_default_markup_setting()
    {
        $this->assertNotNull($this->variety->markup_setting);
    }

    /** @test */
    public function it_can_use_the_account_markup_setting_to_calculate_its_retail_price()
    {
        // Given I have a flower variety that uses the default markup
        $this->assertNotNull($this->variety->use_default_markup);
        $this->assertTrue($this->variety->use_default_markup);

        // and an account markup setting for the item type
        $markup = \App\Markup::whereName('cost_plus_percent')->first();
        $this->variety->markup_setting->markup()->associate($markup);
        $this->variety->markup_setting->save();

        $this->assertNotEquals(
            $this->variety->markup->name,
            $this->variety->markup_setting->markup->name
        );

        // When I get a flower variety, it should use
        // the account markup settings instead of its own
        $markupValue = $this->variety->markup_setting->markup_value;
        $expectedPrice = $this->variety->cost * (1 + $markupValue / 100);
        $this->assertEquals($this->variety->price, $expectedPrice);
    }

    /** @test */
    public function it_can_use_its_own_markup_to_generate_a_retail_price()
    {
        $source = create('App\FlowerVarietySource', [
            'account_id' => $this->variety->account->id,
            'flower_variety_id' => $this->variety->id,
            'cost' => 100,
            'stems_per_bunch' => 10,
        ]);
        $source->variety()->associate($this->variety);
        $source->save();

        $markup = create('App\Markup', ['name' => 'cost_plus_amount']);
        $this->variety->use_default_markup = false;
        $this->variety->markup_value = 5;
        $this->variety->markup()->associate($markup);
        $this->variety->save();

        $this->assertNotEquals(
            $this->variety->markup->name,
            $this->variety->markup_setting->markup->name
        );

        $this->assertNotNull($this->variety->price);
        $this->assertEquals(
            $this->variety->price,
            $this->variety->best_source->cost_per_stem + $this->variety->markup_value
        );
    }

    /** @test */
    public function it_may_have_sources()
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
    public function it_can_have_a_best_source()
    {
        create('App\FlowerVarietySource', [
            'flower_variety_id' => $this->variety->id,
            'vendor_id' => create('App\Vendor'),
            'cost' => 5.0,
            'stems_per_bunch' => 10,
        ]);

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
    public function it_belongs_to_a_flower()
    {
        $this->assertInstanceOf('App\Flower', $this->variety->flower);
    }

    /** @test */
    public function it_belongs_to_a_markup()
    {
        $this->assertInstanceOf('App\Markup', $this->variety->markup);
    }

    /** @test */
    public function it_has_a_markup_value()
    {
        $this->assertNotNull($this->variety->markup_value);
    }

    /** @test */
    public function it_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->variety->account);
    }

    /** @test */
    public function it_can_be_used_as_an_arrangement_ingredient()
    {
        create('App\ArrangementIngredient', [
            'arrangeable_id' => $this->variety->id,
            'arrangeable_type' => 'App\FlowerVariety',
        ]);

        $this->assertInstanceOf('App\ArrangementIngredient', $this->variety->used->first());
    }
}
