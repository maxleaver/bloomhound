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

    /** @test */
    public function an_arrangement_has_a_name() {
        $this->assertNotNull($this->arrangement->name);
    }

    /** @test */
    public function an_arrangement_has_a_quantity() {
        $this->assertNotNull($this->arrangement->quantity);
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
