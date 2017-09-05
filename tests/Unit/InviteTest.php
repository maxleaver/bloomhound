<?php

namespace Tests\Feature;

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
    public function it_generates_a_unique_token_on_creation()
    {
    	$invite = make('App\Invite');
    	$this->assertNull($invite->token);
    	$invite->save();
    	$this->assertNotNull($invite->token);
    }

	/** @test */
    public function it_has_a_path()
    {
        $this->assertNotNull($this->invite->url);
        $this->assertEquals(route('invite', ['token' => $this->invite->token]), $this->invite->url);
    }
}