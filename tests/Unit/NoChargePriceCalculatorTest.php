<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoChargePriceCalculatorTest extends TestCase
{
    use RefreshDatabase;

    protected $item;

    protected function setUp()
    {
        parent::setUp();

        $this->item = create('App\Item', [
            'markup_id' => create('App\Markup', ['name' => 'no_charge'])->id,
            'use_default_markup' => false,
        ]);
    }

    /** @test */
    public function it_returns_zero_regardless_of_cost()
    {
        $this->assertSame($this->item->price, '0.00');
    }
}
