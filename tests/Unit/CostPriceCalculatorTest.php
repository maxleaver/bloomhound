<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CostPriceCalculatorTest extends TestCase
{
    use RefreshDatabase;

    protected $item;

    protected function setUp()
    {
    	parent::setUp();

        $this->item = create('App\Item', [
            'cost' => 500,
            'markup_id' => create('App\Markup', ['name' => 'cost'])->id,
            'use_default_markup' => false,
        ]);
    }

    /** @test */
    public function it_returns_the_items_cost()
    {
        $this->assertSame($this->item->price, '500.00');
    }
}
