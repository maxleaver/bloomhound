<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected $account;

    protected function setUp()
    {
    	parent::setUp();

        $this->account = create('App\Account');
    }

    /** @test */
    public function an_account_has_a_name() {
        $this->assertNotNull($this->account->name);
    }

    /** @test */
    public function an_account_has_an_address() {
        $this->assertNotNull($this->account->address);
    }

    /** @test */
    public function an_account_has_a_website() {
        $this->assertNotNull($this->account->website);
    }

    /** @test */
    public function an_account_has_an_email() {
        $this->assertNotNull($this->account->email);
    }

    /** @test */
    public function an_account_has_a_phone_number() {
        $this->assertNotNull($this->account->phone);
    }

        /** @test */
    public function an_account_has_a_logo() {
        $this->assertNotNull($this->account->logo);
    }

    /** @test */
    public function an_account_has_users()
    {
        create('App\User', [
            'account_id' => $this->account->id
        ]);

        $this->assertInstanceOf('App\User', $this->account->users->first());
    }

    /** @test */
    public function an_account_has_user_invites()
    {
        create('App\Invite', [
            'account_id' => $this->account->id
        ]);

        $this->assertInstanceOf('App\Invite', $this->account->invitations->first());
    }

    /** @test */
    public function an_account_has_customers()
    {
        create('App\Customer', [
            'account_id' => $this->account->id
        ]);

        $this->assertInstanceOf('App\Customer', $this->account->customers->first());
    }

    /** @test */
    public function an_account_has_contacts()
    {
        create('App\Contact', [
            'account_id' => $this->account->id
        ]);

        $this->assertInstanceOf('App\Contact', $this->account->contacts->first());
    }

    /** @test */
    public function an_account_has_events()
    {
        create('App\Event', [
            'account_id' => $this->account->id
        ]);

        $this->assertInstanceOf('App\Event', $this->account->events->first());
    }

    /** @test */
    public function an_account_has_vendors()
    {
        create('App\Vendor', [
            'account_id' => $this->account->id
        ]);

        $this->assertInstanceOf('App\Vendor', $this->account->vendors->first());
    }

    /** @test */
    public function an_account_has_flowers()
    {
        create('App\Flower', [
            'account_id' => $this->account->id
        ]);

        $this->assertInstanceOf('App\Flower', $this->account->flowers->first());
    }

    /** @test */
    public function an_account_has_arrangements()
    {
        create('App\Arrangement', [
            'account_id' => $this->account->id
        ]);

        $this->assertInstanceOf('App\Arrangement', $this->account->arrangements->first());
    }

    /** @test */
    public function an_account_has_flower_variety_sources()
    {
        create('App\FlowerVarietySource', [
            'account_id' => $this->account->id
        ]);

        $this->assertInstanceOf('App\FlowerVarietySource', $this->account->flower_variety_sources->first());
    }

    /** @test */
    public function an_account_has_many_items()
    {
        create('App\Item', ['account_id' => $this->account->id]);

        $this->assertInstanceOf('App\Item', $this->account->items->first());
    }
}
