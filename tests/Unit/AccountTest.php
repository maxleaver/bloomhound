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
}