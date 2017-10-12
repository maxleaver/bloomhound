<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CostPlusAmountPriceCalculatorTest extends TestCase
{
    use RefreshDatabase;

    protected $item;

    protected function setUp()
    {
    	parent::setUp();

        $this->item = create('App\Item', [
        	'cost' => 100,
        	'markup_id' => create('App\Markup', ['name' => 'cost_plus_amount'])->id,
        	'markup_value' => 50,
            'use_default_markup' => false,
        ]);
    }

    /** @test */
    public function it_returns_the_cost_plus_the_markup_value()
    {
        $this->assertSame($this->item->price, '150.00');
    }
}
