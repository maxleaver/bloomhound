<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function a_registered_user_can_request_a_password_reset_email()
    {
        $user = create('App\User');

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertStatus(302);

        Notification::assertSentTo(
            [$user],
            ResetPassword::class
        );
    }

    /** @test */
    public function an_unregistered_user_cannot_receive_a_password_reset_email()
    {
        // Make a user, but don't persist it to the database
        $user = make('App\User');

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertStatus(302);

        Notification::assertNotSentTo(
            [$user],
            ResetPassword::class
        );
    }

    /** @test */
    public function authenticated_users_cant_access_the_registration_form()
    {
        $user = create('App\User');

        $this->actingAs($user)
            ->post(route('password.email'))
            ->assertRedirect(route('home'));
    }
}
