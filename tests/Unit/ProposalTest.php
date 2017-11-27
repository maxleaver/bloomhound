<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProposalTest extends TestCase
{
    use RefreshDatabase;

    protected $proposal;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
    }

    /** @test */
    public function a_proposal_has_a_version_number()
    {
        $this->assertNotNull($this->proposal->version);
    }

    /** @test */
    public function a_proposal_has_a_subtotal()
    {
        $this->assertNotNull($this->proposal->subtotal);
    }

    /** @test */
    public function the_subtotal_includes_arrangements()
    {
        $expected = $this->addArrangements()->sum('total_price');
        $this->assertEquals($expected, $this->proposal->subtotal);
    }

    /** @test */
    public function the_subtotal_includes_deliveries()
    {
        $expected = $this->addDelivery()->sum('fee');
        $this->assertEquals($expected, $this->proposal->subtotal);
    }

    /** @test */
    public function the_subtotal_includes_setups()
    {
        $expected = $this->addSetup()->sum('fee');
        $this->assertEquals($expected, $this->proposal->subtotal);
    }

    /** @test */
    public function fixed_discounts_reduce_the_subtotal()
    {
        $arrangements = $this->addArrangements()->sum('total_price');
        $discount = $this->addDiscount('fixed')->sum('amount');

        $expected = $arrangements - $discount;

        $this->assertEquals($expected, $this->proposal->subtotal);
    }

    /** @test */
    public function percent_discounts_reduce_the_subtotal()
    {
        $arrangements = $this->addArrangements()->sum('total_price');
        $discount = $this->addDiscount('percent')->sum('amount');

        $expected = $arrangements - ($arrangements * ($discount / 100));

        $this->assertEquals($expected, $this->proposal->subtotal);
    }

    /** @test */
    public function percent_discounts_are_applied_before_fixed()
    {
        $arrangements = $this->addArrangements()->sum('total_price');
        $this->addDiscount('percent');
        $this->addDiscount('fixed');

        $discounts = $this->proposal->discounts;
        $percentDiscount = $discounts->where('type', 'percent')->sum('amount');
        $fixedDiscount = $discounts->where('type', 'fixed')->sum('amount');

        $expected = $arrangements;
        $expected -= $arrangements * ($percentDiscount / 100);
        $expected -= $fixedDiscount;

        $this->assertEquals($expected, $this->proposal->subtotal);
    }

    /** @test */
    public function a_proposal_has_a_total()
    {
        $this->assertNotNull($this->proposal->total);
    }

    /** @test */
    public function a_proposal_has_a_tax_amount()
    {
        $this->assertNotNull($this->proposal->tax);
    }

    /** @test */
    public function the_tax_amount_is_the_subtotal_times_the_account_tax_setting()
    {
        $arrangements = $this->addArrangements()->sum('total_price');
        $this->setTaxAmount(10);

        $expected = $this->proposal->subtotal * 0.1;

        $this->assertTrue($this->proposal->subtotal > 0);
        $this->assertEquals($expected, $this->proposal->tax);
    }

    /** @test */
    public function the_total_is_the_subtotal_plus_tax()
    {
        $arrangements = $this->addArrangements()->sum('total_price');
        $this->setTaxAmount(10);

        $expected = $this->proposal->subtotal + ($this->proposal->subtotal * 0.1);

        $this->assertTrue($this->proposal->subtotal > 0);
        $this->assertEquals($expected, $this->proposal->total);
    }

    /** @test */
    public function a_proposal_knows_if_it_is_the_active_proposal_for_its_event()
    {
        $this->assertTrue($this->proposal->isActive);
    }

    /** @test */
    public function a_proposal_has_many_arrangements()
    {
        $this->addArrangements();
        $this->assertInstanceOf('App\Arrangement', $this->proposal->arrangements->first());
    }

    /** @test */
    public function a_proposal_has_many_deliveries()
    {
        $this->addDelivery();
        $this->assertInstanceOf('App\Delivery', $this->proposal->deliveries->first());
    }

    /** @test */
    public function a_proposal_can_have_discounts()
    {
        $this->addDiscount();
        $this->assertInstanceOf('App\Discount', $this->proposal->discounts->first());
    }

    /** @test */
    public function a_proposal_belongs_to_an_event()
    {
        $this->assertInstanceOf('App\Event', $this->proposal->event);
    }

    /** @test */
    public function a_proposal_has_many_setups()
    {
        $this->addSetup();
        $this->assertInstanceOf('App\Setup', $this->proposal->setups->first());
    }

    /** @test */
    public function a_proposal_has_many_vendors()
    {
        $vendors = create('App\Vendor', [], 10);
        $this->proposal->vendors()->attach($vendors);

        $this->assertInstanceOf('App\Vendor', $this->proposal->vendors->first());
    }

    protected function addArrangements()
    {
        return factory('App\Arrangement', 2)
            ->states('override_price')
            ->create(['proposal_id' => $this->proposal->id]);
    }

    protected function addDelivery()
    {
        return create('App\Delivery', ['proposal_id' => $this->proposal->id]);
    }

    protected function addDiscount($type = 'fixed')
    {
        return factory('App\Discount')
            ->states('proposal', $type)
            ->create(['discountable_id' => $this->proposal->id]);
    }

    protected function addSetup()
    {
        return create('App\Setup', ['proposal_id' => $this->proposal->id]);
    }

    protected function setTaxAmount($amount)
    {
        $this->proposal
            ->event
            ->account
            ->settings
            ->update([
                'use_tax' => true,
                'tax_amount' => $amount,
            ]);
    }
}
