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
    public function a_vendor_has_a_phone_number()
    {
        $this->assertNotNull($this->vendor->phone);
    }

    /** @test */
    public function a_vendor_has_an_email()
    {
        $this->assertNotNull($this->vendor->email);
    }

    /** @test */
    public function a_vendor_has_a_website()
    {
        $this->assertNotNull($this->vendor->website);
    }

    /** @test */
    public function a_vendor_has_an_address()
    {
        $this->assertNotNull($this->vendor->address);
    }

    /** @test */
    public function a_vendor_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->vendor->account);
    }

    /** @test */
    public function a_vendor_can_have_many_events()
    {
        $events = create('App\Event', [], 10);
        $this->vendor->events()->attach($events);

        $this->assertInstanceOf('App\Event', $this->vendor->events->first());
    }

    /** @test */
    public function a_vendor_can_have_many_notes()
    {
        create('App\Note', [
            'user_id' => create('App\User', ['account_id' => $this->vendor->account->id]),
            'notable_id' => $this->vendor->id,
            'notable_type' => 'App\Vendor',
            'text' => 'This is a note.',
        ]);

        $this->assertInstanceOf('App\Note', $this->vendor->notes->first());
    }
}
