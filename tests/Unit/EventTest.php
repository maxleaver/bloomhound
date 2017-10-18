<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    protected $event;

	protected function setUp()
    {
    	parent::setUp();

        $this->event = create('App\Event');
    }

    /** @test */
    public function an_event_has_a_name()
    {
    	$this->assertNotNull($this->event->name);
    }

    /** @test */
    public function an_event_has_a_date()
    {
        $this->assertNotNull($this->event->name);
    }

    /** @test */
    public function an_event_belongs_to_a_status()
    {
        $this->assertInstanceOf('App\EventStatus', $this->event->status);
    }

    /** @test */
    public function an_event_belongs_to_a_customer()
    {
        $this->assertInstanceOf('App\Customer', $this->event->customer);
    }

    /** @test */
    public function an_event_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->event->account);
    }

    /** @test */
    public function an_event_can_have_many_arrangements()
    {
        create('App\Arrangement', [
            'account_id' => $this->event->account->id,
            'event_id' => $this->event->id,
        ], 10);

        $this->assertInstanceOf('App\Arrangement', $this->event->arrangements->first());
    }

    /** @test */
    public function an_event_can_have_many_deliveries()
    {
        create('App\Delivery', [
            'account_id' => $this->event->account->id,
            'event_id' => $this->event->id,
        ], 10);

        $this->assertInstanceOf('App\Delivery', $this->event->deliveries->first());
    }

    /** @test */
    public function an_event_can_have_many_setups()
    {
        create('App\Setup', [
            'account_id' => $this->event->account->id,
            'event_id' => $this->event->id,
        ], 10);

        $this->assertInstanceOf('App\Setup', $this->event->setups->first());
    }

    /** @test */
    public function an_event_can_have_many_vendors()
    {
        $vendors = create('App\Vendor', [], 10);
        $this->event->vendors()->attach($vendors);

        $this->assertInstanceOf('App\Vendor', $this->event->vendors->first());
    }

    /** @test */
    public function an_event_can_have_many_notes()
    {
        create('App\Note', [
            'user_id' => create('App\User', ['account_id' => $this->event->account->id]),
            'notable_id' => $this->event->id,
            'notable_type' => 'App\Event',
            'text' => 'This is a note.',
        ]);

        $this->assertInstanceOf('App\Note', $this->event->notes->first());
    }
}
