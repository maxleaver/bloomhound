<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InviteTest extends TestCase
{
    use RefreshDatabase;

    protected $invite;

	protected function setUp()
    {
    	parent::setUp();

        $this->invite = create('App\Invite');
    }

    /** @test */
    public function an_invite_generates_a_unique_token_on_creation()
    {
    	$invite = make('App\Invite');
    	$this->assertNull($invite->token);
    	$invite->save();
    	$this->assertNotNull($invite->token);
    }

	/** @test */
    public function an_invite_has_a_path()
    {
        $this->assertNotNull($this->invite->url);
        $this->assertEquals(route('invite', ['token' => $this->invite->token]), $this->invite->url);
    }

    /** @test */
    public function an_invite_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->invite->account);
    }
}
