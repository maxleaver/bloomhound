<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArrangementIngredientTest extends TestCase
{
    use RefreshDatabase;

    protected $ingredient;

    protected function setUp()
    {
    	parent::setUp();

        $this->ingredient = create('App\ArrangementIngredient');
    }

    /** @test */
    public function an_ingredient_has_a_quantity() {
        $this->assertNotNull($this->ingredient->quantity);
    }

    /** @test */
    public function an_ingredient_belongs_to_an_arrangement()
    {
        $this->assertInstanceOf('App\Arrangement', $this->ingredient->arrangement);
    }

    /** @test */
    public function an_ingredient_has_an_arrangeable()
    {
        $ingredient = create('App\ArrangementIngredient', [
            'arrangeable_id' => create('App\Item')->id,
            'arrangeable_type' => 'App\Item',
        ]);

        $this->assertInstanceOf('App\Item', $ingredient->arrangeable);
    }
}
