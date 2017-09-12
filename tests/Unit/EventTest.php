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
    public function an_event_has_a_status()
    {
        $this->assertInstanceOf('App\EventStatus', $this->event->status);
    }

    /** @test */
    public function an_event_has_a_customer()
    {
        $this->assertInstanceOf('App\Customer', $this->event->customer);
    }

    /** @test */
    public function an_event_has_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->event->account);
    }
}
