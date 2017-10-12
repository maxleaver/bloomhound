<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarkupTest extends TestCase
{
	use RefreshDatabase;

	protected $markup;

	protected function setUp()
    {
    	parent::setUp();

        $this->markup = create('App\Markup');
    }

	/** @test */
    public function it_has_a_name()
    {
        $this->assertNotNull($this->markup->name);
    }

    /** @test */
    public function it_has_a_title()
    {
        $this->assertNotNull($this->markup->title);
    }

    /** @test */
    public function it_has_a_description()
    {
        $this->assertNotNull($this->markup->title);
    }

    /** @test */
    public function it_may_allow_field_entry()
    {
        $this->assertNotNull($this->markup->allow_entry);
    }

    /** @test */
    public function it_may_have_a_field_label()
    {
        $this->assertNotNull($this->markup->field_label);
    }

    /** @test */
    public function it_has_many_flower_varieties()
    {
        create('App\FlowerVariety', [
            'markup_id' => $this->markup->id
        ]);

        $this->assertInstanceOf('App\FlowerVariety', $this->markup->flower_varieties->first());
    }

    /** @test */
    public function it_has_many_items()
    {
        create('App\Item', [
            'markup_id' => $this->markup->id
        ]);

        $this->assertInstanceOf('App\Item', $this->markup->items->first());
    }

    /** @test */
    public function it_has_many_arrangeable_type_settings()
    {
        create('App\ArrangeableTypeSetting', [
            'markup_id' => $this->markup->id
        ]);

        $setting = $this->markup->settings->first();
        $this->assertInstanceOf('App\ArrangeableTypeSetting', $setting);
    }
}
