<?php

namespace Tests\Feature;

use App\Account;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_registered_user_can_request_an_auth_token()
    {
        $this->withoutExceptionHandling();

        $user = create('App\User', [
            'password' => bcrypt('abc123')
        ]);

        $request = [
            'email' => $user->email,
            'password' => 'abc123'
        ];

        $response = $this->json('POST', 'api/auth', $request)
        	->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function an_unregistered_users_will_not_receive_an_auth_token()
    {
        $this->withoutExceptionHandling();

        $user = create('App\User', [
            'password' => bcrypt('abc123')
        ]);

        $request = [
            'email' => $user->email,
            'password' => 'abc123'
        ];

        $response = $this->json('POST', 'api/auth', $request)
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function a_user_can_only_get_an_auth_token_with_a_correct_password()
    {
        $user = create('App\User');

        $request = [
            'email' => $user->email,
            'password' => 'The Wrong Password'
        ];

        $response = $this->json('POST', 'api/auth', $request)
            ->assertStatus(422)
            ->assertJsonFragment(['errors']);
    }
}