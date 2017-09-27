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
    public function an_event_has_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->event->account);
    }

    /** @test */
    public function an_event_may_have_many_arrangements()
    {
        $arrangements = create('App\Arrangement', [
            'account_id' => $this->event->account->id,
            'event_id' => $this->event->id,
        ], 10);

        $this->assertInstanceOf('App\Arrangement', $this->event->arrangements->first());
    }

    /** @test */
    public function an_event_has_notes()
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
