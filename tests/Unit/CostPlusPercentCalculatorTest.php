<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CostPlusPercentCalculatorTest extends TestCase
{
    use RefreshDatabase;

    protected $item;

    protected function setUp()
    {
    	parent::setUp();

        $this->item = create('App\Item', [
            'cost' => 100,
            'markup_id' => create('App\Markup', ['name' => 'cost_plus_percent'])->id,
            'markup_value' => 10,
            'use_default_markup' => false,
        ]);
    }

    /** @test */
    public function it_returns_the_cost_plus_a_percentage()
    {
        $this->assertSame($this->item->price, '110.00');
    }
}
