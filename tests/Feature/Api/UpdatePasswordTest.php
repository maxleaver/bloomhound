<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User', [
            'password' => Hash::make('abc123')
        ]);
        $this->url = 'api/password';
        $this->request = [
            'current_password' => 'abc123',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
    }

    /** @test */
    public function a_user_can_update_their_password()
    {
        $oldHash = $this->user->password;

    	$this->signIn($this->user)
            ->patchJson($this->url, $this->request)
    		->assertStatus(200);

        $this->assertNotEquals($this->user->fresh()->password, $oldHash);
    }

    /** @test */
    public function a_user_can_only_update_their_password_if_they_provide_the_correct_old_password()
    {
        $this->request['current_password'] = 'theWrongPassword';

        $this->signIn($this->user)
            ->patchJson($this->url, $this->request)
            ->assertStatus(401);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_passwords()
    {
    	$this->patchJson($this->url, $this->request)
    		->assertStatus(401);
    }
}
