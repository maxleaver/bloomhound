<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected $contact;

    protected function setUp()
    {
    	parent::setUp();

        $this->contact = create('App\Contact');
    }

    /** @test */
    public function it_has_a_first_name() {
        $this->assertNotNull($this->contact->first_name);
    }

    /** @test */
    public function it_has_a_last_name() {
        $this->assertNotNull($this->contact->last_name);
    }

    /** @test */
    public function it_has_an_email() {
        $this->assertNotNull($this->contact->email);
    }

    /** @test */
    public function it_has_a_phone_number() {
        $this->assertNotNull($this->contact->phone);
    }

    /** @test */
    public function it_has_an_address() {
        $this->assertNotNull($this->contact->address);
    }

    /** @test */
    public function it_has_a_relationship_field() {
        $this->assertNotNull($this->contact->relationship);
    }

    /** @test */
    public function it_is_assigned_to_an_account() {
        $this->assertInstanceOf('App\Account', $this->contact->account);
    }

    /** @test */
    public function it_is_assigned_to_a_customer() {
        $this->assertInstanceOf('App\Customer', $this->contact->customer);
    }

    /** @test */
    public function it_has_a_name_attribute()
    {
        $this->assertEquals(
            $this->contact->name,
            $this->contact->first_name . ' ' . $this->contact->last_name
        );
    }

    /** @test */
    public function a_contact_has_notes()
    {
        create('App\Note', [
            'user_id' => create('App\User', ['account_id' => $this->contact->account->id]),
            'notable_id' => $this->contact->id,
            'notable_type' => 'App\Contact',
            'text' => 'This is a note.',
        ]);

        $this->assertInstanceOf('App\Note', $this->contact->notes->first());
    }
}