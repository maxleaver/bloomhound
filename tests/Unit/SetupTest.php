<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SetupTest extends TestCase
{
    use RefreshDatabase;

    protected $setup;

    protected function setUp()
    {
    	parent::setUp();

        $this->setup = create('App\Setup');
    }

    /** @test */
    public function an_event_setup_has_an_address() {
        $this->assertNotNull($this->setup->address);
    }

    /** @test */
    public function an_event_setup_has_a_setup_date_and_time() {
        $this->assertNotNull($this->setup->setup_on);
    }

    /** @test */
    public function an_event_setup_may_have_a_description() {
        $this->assertNotNull($this->setup->description);
    }

    /** @test */
    public function an_event_setup_may_have_a_fee() {
        $this->assertNotNull($this->setup->fee);
    }

    /** @test */
    public function an_event_setup_belongs_to_an_account() {
        $this->assertInstanceOf('App\Account', $this->setup->account);
    }

    /** @test */
    public function an_event_setup_belongs_to_an_event() {
        $this->assertInstanceOf('App\Event', $this->setup->event);
    }
}
