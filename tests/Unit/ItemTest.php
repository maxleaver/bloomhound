<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
	use RefreshDatabase;

	protected $item;

	protected function setUp()
    {
    	parent::setUp();

        $this->item = create('App\Item');
    }

	/** @test */
    public function it_has_a_name()
    {
        $this->assertNotNull($this->item->name);
    }

    /** @test */
    public function it_has_an_ingredient_name()
    {
        $this->assertNotNull($this->item->ingredient_name);
        $this->assertEquals($this->item->ingredient_name, $this->item->name);
    }

    /** @test */
    public function it_has_a_description()
    {
        $this->assertNotNull($this->item->description);
    }

    /** @test */
    public function it_has_an_inventory_count()
    {
        $this->assertNotNull($this->item->inventory);
    }

    /** @test */
    public function it_can_have_a_cost()
    {
        $this->assertNotNull($this->item->cost);
    }

    /** @test */
    public function it_has_a_retail_price()
    {
        $this->assertNotNull($this->item->price);
    }

    /** @test */
    public function it_has_a_use_default_markup_setting()
    {
        $this->assertNotNull($this->item->use_default_markup);
        $this->assertTrue($this->item->use_default_markup);
    }

    /** @test */
    public function it_has_a_default_markup_setting()
    {
        $this->assertNotNull($this->item->markup_setting);
    }

    /** @test */
    public function it_can_use_the_account_markup_setting_to_calculate_its_retail_price()
    {
        // Given I have an item set to use the default markup
        $this->assertNotNull($this->item->use_default_markup);
        $this->assertTrue($this->item->use_default_markup);

        // and an account markup setting for the item type
        $markup = \App\Markup::whereName('cost_plus_percent')->first();
        $this->item->markup_setting->markup()->associate($markup);
        $this->item->markup_setting->save();

        $this->assertNotEquals(
            $this->item->markup->name,
            $this->item->markup_setting->markup->name
        );

        // When I get an item, then it should use the account markup settings
        // instead of its own
        $markupValue = $this->item->markup_setting->markup_value;
        $expectedPrice = $this->item->cost * (1 + $markupValue / 100);
        $this->assertEquals($this->item->price, $expectedPrice);
    }

    /** @test */
    public function it_can_use_its_own_markup_to_generate_a_retail_price()
    {
        $markup = create('App\Markup', ['name' => 'cost_plus_amount']);
        $this->item->use_default_markup = false;
        $this->item->markup()->associate($markup);
        $this->item->save();

        $this->assertNotEquals(
            $this->item->markup->name,
            $this->item->markup_setting->markup->name
        );

        $this->assertEquals(
            $this->item->price,
            $this->item->cost + $this->item->markup_value
        );
    }

    /** @test */
    public function it_has_an_arrangeable_type()
    {
        $this->assertNotNull($this->item->arrangeable_type);
        $this->assertEquals($this->item->arrangeable_type, 'item');
    }

    /** @test */
    public function it_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->item->account);
    }

    /** @test */
    public function it_belongs_to_a_markup()
    {
        $this->assertInstanceOf('App\Markup', $this->item->markup);
    }

    /** @test */
    public function it_has_a_markup_value()
    {
        $this->assertNotNull($this->item->markup_value);
    }

    /** @test */
    public function it_has_notes()
    {
        create('App\Note', [
            'user_id' => create('App\User', ['account_id' => $this->item->account->id]),
            'notable_id' => $this->item->id,
            'notable_type' => 'App\Item',
            'text' => 'This is a note.',
        ]);

        $this->assertInstanceOf('App\Note', $this->item->notes->first());
    }

    /** @test */
    public function it_can_be_used_as_an_arrangement_ingredient()
    {
        create('App\ArrangementIngredient', [
            'arrangeable_id' => $this->item->id,
            'arrangeable_type' => 'App\Item',
        ]);

        $this->assertInstanceOf('App\ArrangementIngredient', $this->item->used->first());
    }
}
