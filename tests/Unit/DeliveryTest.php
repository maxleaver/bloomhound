<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeliveryTest extends TestCase
{
    use RefreshDatabase;

    protected $delivery;

    protected function setUp()
    {
    	parent::setUp();

        $this->delivery = create('App\Delivery');
    }

    /** @test */
    public function a_delivery_has_an_address() {
        $this->assertNotNull($this->delivery->address);
    }

    /** @test */
    public function a_delivery_has_a_deliver_by_date_and_time() {
        $this->assertNotNull($this->delivery->deliver_on);
    }

    /** @test */
    public function a_delivery_may_have_a_description() {
        $this->assertNotNull($this->delivery->description);
    }

    /** @test */
    public function a_delivery_may_have_a_fee() {
        $this->assertNotNull($this->delivery->fee);
    }

    /** @test */
    public function a_delivery_belongs_to_an_account() {
        $this->assertInstanceOf('App\Account', $this->delivery->account);
    }

    /** @test */
    public function a_delivery_belongs_to_an_event() {
        $this->assertInstanceOf('App\Event', $this->delivery->event);
    }
}
