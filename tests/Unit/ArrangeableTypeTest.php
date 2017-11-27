<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArrangeableTypeTest extends TestCase
{
    use RefreshDatabase;

    protected $type;

    protected function setUp()
    {
        parent::setUp();

        $this->type = create('App\ArrangeableType');
    }

    /** @test */
    public function an_arrangeable_type_has_a_name()
    {
        $this->assertNotNull($this->type->name);
    }

    /** @test */
    public function an_arrangeable_type_has_a_title()
    {
        $this->assertNotNull($this->type->title);
    }

    /** @test */
    public function an_arrangeable_type_specifies_the_model_it_is_for()
    {
        $this->assertNotNull($this->type->model);
    }

    /** @test */
    public function an_arrangeable_type_belongs_to_a_default_markup()
    {
        $this->assertInstanceOf('App\Markup', $this->type->markup);
    }

    /** @test */
    public function an_arrangeable_type_can_have_many_items()
    {
        create('App\Item', [
            'arrangeable_type_id' => $this->type->id,
        ]);

        $this->assertInstanceOf('App\Item', $this->type->items->first());
    }

    /** @test */
    public function an_arrangeable_type_can_have_many_flower_varieties()
    {
        create('App\FlowerVariety', [
            'arrangeable_type_id' => $this->type->id,
        ]);

        $this->assertInstanceOf('App\FlowerVariety', $this->type->flower_varieties->first());
    }
}
