<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
    }

    /** @test */
    public function a_user_has_a_name()
    {
        $this->assertNotNull($this->user->name);
    }

    /** @test */
    public function a_user_has_an_email()
    {
        $this->assertNotNull($this->user->email);
    }

    /** @test */
    public function a_user_has_a_password()
    {
        $this->assertNotNull($this->user->password);
    }

    /** @test */
    public function a_user_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->user->account);
    }

    /** @test */
    public function a_user_has_notes()
    {
        create('App\Note', [
            'user_id' => $this->user->id
        ]);

        $this->assertInstanceOf('App\Note', $this->user->notes->first());
    }
}
