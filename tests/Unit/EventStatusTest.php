<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventStatusTest extends TestCase
{
    use RefreshDatabase;

    protected $status;

    protected function setUp()
    {
        parent::setUp();

        $this->status = create('App\EventStatus');
    }

    /** @test */
    public function an_event_status_has_a_name()
    {
        $this->assertNotNull($this->status->name);
    }

    /** @test */
    public function an_event_status_has_a_title()
    {
        $this->assertNotNull($this->status->title);
    }

    /** @test */
    public function an_event_status_has_events()
    {
        create('App\Event', ['status_id' => $this->status->id]);

        $this->assertInstanceOf('App\Event', $this->status->events->first());
    }
}
