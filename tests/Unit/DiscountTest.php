<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiscountTest extends TestCase
{
    use RefreshDatabase;

    protected $discount;

    protected function setUp()
    {
        parent::setUp();

        $arrangement = create('App\Arrangement');
        $this->discount = create('App\Discount', [
            'discountable_id' => $arrangement->id,
            'discountable_type' => 'App\Arrangement',
        ]);
    }

    /** @test */
    public function a_discount_has_a_name()
    {
        $this->assertNotNull($this->discount->name);
    }

    /** @test */
    public function a_discount_has_an_amount()
    {
        $this->assertNotNull($this->discount->amount);
    }

    /** @test */
    public function a_discount_has_a_type()
    {
        $this->assertNotNull($this->discount->type);
    }

    /** @test */
    public function a_discount_has_a_discountable()
    {
        $this->assertInstanceOf('App\Arrangement', $this->discount->discountable);
    }
}
