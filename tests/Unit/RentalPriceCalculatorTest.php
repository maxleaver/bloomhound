<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RentalPriceCalculatorTest extends TestCase
{
    use RefreshDatabase;

    protected $item;

    protected function setUp()
    {
    	parent::setUp();

        $this->item = create('App\Item', [
            'cost' => 500,
            'markup_id' => create('App\Markup', ['name' => 'amount_times_event_days'])->id,
            'markup_value' => 100,
            'use_default_markup' => false,
        ]);
    }

    /** @test */
    public function it_returns_the_items_markup_value()
    {
        $this->assertSame($this->item->price, '100.00');
    }
}
