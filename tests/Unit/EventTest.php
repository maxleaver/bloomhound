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

    /** @test */
    public function an_event_can_have_many_proposals()
    {
        create('App\Proposal', [
            'event_id' => $this->event->id,
        ], 3);

        $this->assertInstanceOf('App\Proposal', $this->event->proposals->first());
    }

    /** @test */
    public function an_event_has_one_active_proposal()
    {
        create('App\Proposal', [
            'event_id' => $this->event->id,
        ]);

        $this->assertInstanceOf('App\Proposal', $this->event->active_proposal);
    }

    /** @test */
    public function on_creation_an_event_generates_an_initial_proposal()
    {
        $this->assertInstanceOf('App\Proposal', $this->event->proposals->first());
        $this->assertEquals(1, $this->event->proposals->first()->version);
    }

    /** @test */
    public function an_event_can_set_an_active_proposal()
    {
        $firstProposal = $this->event->proposals()->first();
        $secondProposal = create('App\Proposal', [
            'event_id' => $this->event->id
        ]);

        $this->assertTrue($secondProposal->isActive);
        $this->assertFalse($firstProposal->isActive);
        $this->event->fresh()->setActiveProposal($firstProposal);
        $this->assertTrue($firstProposal->isActive);
        $this->assertFalse($secondProposal->isActive);
    }
}
