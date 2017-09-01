<?php

namespace Tests\Feature;

use App\Account;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function a_registered_user_can_request_a_password_reset_email()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $response = $this->json('POST', 'api/password/email', ['email' => $user->email])
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        Notification::assertSentTo(
            [$user], ResetPassword::class
        );
    }

    /** @test */
    public function an_unregistered_user_cannot_receive_a_password_reset_email()
    {
        Notification::fake();

        // Make a user, but don't persist it
        // so it won't exist in the database
        $user = factory(User::class)->make();

        $response = $this->json('POST', 'api/password/email', ['email' => $user->email])
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        Notification::assertNotSentTo(
            [$user], ResetPassword::class
        );
    }
}