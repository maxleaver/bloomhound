<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlowerVarietyTest extends TestCase
{
    use RefreshDatabase;

    protected $variety;

	protected function setUp()
    {
    	parent::setUp();

        $this->variety = create('App\FlowerVariety');
    }

    /** @test */
    public function a_flower_variety_has_a_name()
    {
        $this->assertNotNull($this->variety->name);
    }

    /** @test */
    public function a_flower_variety_belongs_to_a_flower()
    {
    	$this->assertInstanceOf('App\Flower', $this->variety->flower);
    }
}
