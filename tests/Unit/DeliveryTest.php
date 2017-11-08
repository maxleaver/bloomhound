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
    public function a_delivery_has_many_arrangements() {
        $arrangements = create('App\Arrangement', [
            'account_id' => $this->delivery->account->id,
            'delivery_id' => $this->delivery->id,
            'proposal_id' => $this->delivery->proposal->id,
        ]);

        $this->assertInstanceOf('App\Arrangement', $this->delivery->arrangements->first());
    }

    /** @test */
    public function a_delivery_belongs_to_an_account() {
        $this->assertInstanceOf('App\Account', $this->delivery->account);
    }

    /** @test */
    public function a_delivery_belongs_to_a_proposal() {
        $this->assertInstanceOf('App\Proposal', $this->delivery->proposal);
    }
}
