<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTypeTest extends TestCase
{
	use RefreshDatabase;

	protected $type;

	protected function setUp()
    {
    	parent::setUp();

        $this->type = create('App\ItemType');
    }

	/** @test */
    public function an_item_type_has_a_name()
    {
        $this->assertNotNull($this->type->name);
    }

    /** @test */
    public function an_item_type_has_a_title()
    {
        $this->assertNotNull($this->type->title);
    }

    /** @test */
    public function an_item_type_has_items()
    {
        create('App\Item', [
            'item_type_id' => $this->type->id,
        ]);

        $this->assertInstanceOf('App\Item', $this->type->items->first());
    }
}
