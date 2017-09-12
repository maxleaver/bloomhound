<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorTest extends TestCase
{
	use RefreshDatabase;

	protected $vendor;

	protected function setUp()
    {
    	parent::setUp();

        $this->vendor = create('App\Vendor');
    }

	/** @test */
    public function a_vendor_has_a_name()
    {
        $this->assertNotNull($this->vendor->name);
    }

    /** @test */
    public function a_vendor_has_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->vendor->account);
    }
}
