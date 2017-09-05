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
}