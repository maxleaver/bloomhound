<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArrangementTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangement;

    protected function setUp()
    {
    	parent::setUp();

        $this->arrangement = create('App\Arrangement');
    }

    protected function addIngredients($arrangement, $type, $amount)
    {
        create('App\ArrangementIngredient', [
            'arrangement_id' => $arrangement->id,
            'arrangeable_id' => create($type)->id,
            'arrangeable_type' => $type
        ], $amount);
    }

    protected function sumIngredientsField($name)
    {
        $total = 0;
        $this->arrangement
            ->ingredients()
            ->each(function ($ingredient) use (&$total, $name) {
                $qty = $ingredient->quantity;
                $field = $ingredient->arrangeable[$name];
                $total += $qty * $field;
            });
        return $total;
    }

    /** @test */
    public function an_arrangement_has_a_name() {
        $this->assertNotNull($this->arrangement->name);
    }

    /** @test */
    public function an_arrangement_has_a_quantity() {
        $this->assertNotNull($this->arrangement->quantity);
    }

    /** @test */
    public function an_arrangement_has_a_description() {
        $this->assertNotNull($this->arrangement->description);
    }

    /** @test */
    public function an_arrangement_uses_its_ingredients_to_calculate_a_total_cost()
    {
        $this->addIngredients($this->arrangement, 'App\Item', 5);
        $this->addIngredients($this->arrangement, 'App\FlowerVariety', 5);

        $expectedCost = $this->sumIngredientsField('cost');

        $this->assertNotNull($this->arrangement->cost);
        $this->assertEquals($this->arrangement->cost, $expectedCost);
    }

    /** @test */
    public function an_arrangement_uses_its_ingredients_to_calculate_a_default_price()
    {
        $this->addIngredients($this->arrangement, 'App\Item', 5);
        $this->addIngredients($this->arrangement, 'App\FlowerVariety', 5);

        $expectedPrice = $this->sumIngredientsField('price');

        $this->assertNotNull($this->arrangement->default_price);
        $this->assertEquals($this->arrangement->default_price, $expectedPrice);
    }

    /** @test */
    public function an_arrangement_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->arrangement->account);
    }

    /** @test */
    public function an_arrangement_belongs_to_an_event()
    {
        $this->assertInstanceOf('App\Event', $this->arrangement->event);
    }

    /** @test */
    public function an_arrangement_can_have_ingredients()
    {
        create('App\ArrangementIngredient', [
            'arrangement_id' => $this->arrangement->id,
            'arrangeable_id' => create('App\Item')->id,
            'arrangeable_type' => 'App\Item',
        ]);

        $this->assertInstanceOf(
            'App\ArrangementIngredient',
            $this->arrangement->ingredients->first()
        );
    }
}
