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
    public function an_arrangement_has_a_cost_per_unit()
    {
        $this->addIngredients($this->arrangement, 'App\Item', 5);
        $this->addIngredients($this->arrangement, 'App\FlowerVariety', 5);

        $expectedCost = $this->arrangement->ingredients->sum('cost');

        $this->assertNotNull($this->arrangement->cost);
        $this->assertEquals($this->arrangement->cost, $expectedCost);
    }

    /** @test */
    public function an_arrangement_has_a_price_per_unit()
    {
        $this->addIngredients($this->arrangement, 'App\Item', 5);
        $this->addIngredients($this->arrangement, 'App\FlowerVariety', 5);

        $expectedPrice = $this->arrangement->ingredients->sum('price');

        $this->assertNotNull($this->arrangement->price);
        $this->assertEquals($this->arrangement->price, $expectedPrice);
    }

    /** @test */
    public function an_arrangement_has_a_total_price()
    {
        $this->addIngredients($this->arrangement, 'App\Item', 5);
        $this->addIngredients($this->arrangement, 'App\FlowerVariety', 5);

        $expectedPrice = $this->arrangement->ingredients->sum('price') * $this->arrangement->quantity;

        $this->assertNotNull($this->arrangement->totalPrice);
        $this->assertEquals($this->arrangement->totalPrice, $expectedPrice);
    }

    /** @test */
    public function an_arrangement_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->arrangement->account);
    }

    /** @test */
    public function an_arrangement_may_belong_to_a_delivery()
    {
        $this->assertInstanceOf('App\Delivery', $this->arrangement->delivery);
    }

    /** @test */
    public function an_arrangement_belongs_to_an_event()
    {
        $this->assertInstanceOf('App\Event', $this->arrangement->event);
    }

    /** @test */
    public function an_arrangement_can_have_discounts()
    {
        create('App\Discount', [
            'account_id' => $this->arrangement->account->id,
            'discountable_id' => $this->arrangement->id,
            'discountable_type' => 'App\Arrangement',
        ]);

        $this->assertInstanceOf('App\Discount', $this->arrangement->discounts->first());
    }

    /** @test */
    public function discounts_affect_the_total_price()
    {
        $this->addIngredients($this->arrangement, 'App\Item', 5);
        $this->addIngredients($this->arrangement, 'App\FlowerVariety', 5);
        create('App\Discount', [
            'discountable_id' => $this->arrangement->id,
            'discountable_type' => 'App\Arrangement',
            'type' => 'fixed',
            'amount' => 10,
        ], 2);

        create('App\Discount', [
            'discountable_id' => $this->arrangement->id,
            'discountable_type' => 'App\Arrangement',
            'type' => 'percent',
            'amount' => 10,
        ], 2);

        $originalPrice = $this->arrangement->ingredients->sum('price') * $this->arrangement->quantity;
        $expectedPrice = $originalPrice - ($originalPrice * 0.2) - 20;

        $this->assertNotNull($this->arrangement->totalPrice);
        $this->assertEquals($this->arrangement->totalPrice, $expectedPrice);
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
