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
    public function a_proposal_knows_if_it_is_the_active_proposal_for_its_event()
    {
        $this->assertTrue($this->proposal->isActive);
    }

    /** @test */
    public function a_proposal_has_many_arrangements()
    {
        create('App\Arrangement', [
            'proposal_id' => $this->proposal->id,
        ]);

        $this->assertInstanceOf(
            'App\Arrangement',
            $this->proposal->arrangements->first()
        );
    }

    /** @test */
    public function a_proposal_has_many_deliveries()
    {
        create('App\Delivery', [
            'proposal_id' => $this->proposal->id,
        ]);

        $this->assertInstanceOf(
            'App\Delivery',
            $this->proposal->deliveries->first()
        );
    }

    /** @test */
    public function a_proposal_belongs_to_an_event()
    {
        $this->assertInstanceOf('App\Event', $this->proposal->event);
    }

    /** @test */
    public function a_proposal_has_many_setups()
    {
        create('App\Setup', [
            'proposal_id' => $this->proposal->id,
        ]);

        $this->assertInstanceOf('App\Setup', $this->proposal->setups->first());
    }

    /** @test */
    public function a_proposal_belongs_to_many_vendors()
    {
        $vendors = create('App\Vendor', [], 10);
        $this->proposal->vendors()->attach($vendors);

        $this->assertInstanceOf('App\Vendor', $this->proposal->vendors->first());
    }
}
