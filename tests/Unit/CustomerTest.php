<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;

	protected function setUp()
    {
    	parent::setUp();

        $this->customer = create('App\Customer');
    }

    /** @test */
    public function a_customer_has_a_name()
    {
    	$this->assertNotNull($this->customer->name);
    }

    /** @test */
    public function a_customer_has_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->customer->account);
    }

    /** @test */
    public function a_customer_has_contacts()
    {
        create('App\Contact', [
            'customer_id' => $this->customer->id
        ]);

        $this->assertInstanceOf('App\Contact', $this->customer->contacts->first());
    }

    /** @test */
    public function a_customer_has_events()
    {
        create('App\Event', [
            'customer_id' => $this->customer->id
        ]);

        $this->assertInstanceOf('App\Event', $this->customer->events->first());
    }

    /** @test */
    public function a_customer_has_notes()
    {
        create('App\Note', [
            'user_id' => create('App\User', ['account_id' => $this->customer->account->id]),
            'notable_id' => $this->customer->id,
            'notable_type' => 'App\Customer',
            'text' => 'This is a note.',
        ]);

        $this->assertInstanceOf('App\Note', $this->customer->notes->first());
    }
}