<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
	use RefreshDatabase;

	protected $item;

	protected function setUp()
    {
    	parent::setUp();

        $this->item = create('App\Item');
    }

	/** @test */
    public function an_item_has_a_name()
    {
        $this->assertNotNull($this->item->name);
    }

    /** @test */
    public function an_item_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->item->account);
    }

    /** @test */
    public function an_item_has_notes()
    {
        create('App\Note', [
            'user_id' => create('App\User', ['account_id' => $this->item->account->id]),
            'notable_id' => $this->item->id,
            'notable_type' => 'App\Item',
            'text' => 'This is a note.',
        ]);

        $this->assertInstanceOf('App\Note', $this->item->notes->first());
    }
}
