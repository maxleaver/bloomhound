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

        $user = factory(User::class)->create([
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

        $user = factory(User::class)->create([
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
}