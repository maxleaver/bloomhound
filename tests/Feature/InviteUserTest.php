<?php

namespace Tests\Feature;

use App\User;
use App\Invite;
use App\Mail\UserInvited;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class InviteUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_invite_other_users()
    {
        $this->withoutExceptionHandling();

        Mail::fake();

        $user = create(User::class);
        $request = ['email' => 'john@doe.com'];

        $this->assertEquals(Invite::count(), 0);

        $response = $this->json('POST', 'api/users', $request, $this->getAuthHeader($user))
        	->assertStatus(200);

        $this->assertEquals(Invite::count(), 1);

        Mail::assertSent(UserInvited::class, function ($mail) use ($user) {
            return $mail->hasTo('john@doe.com');
        });
    }

    /** @test */
    public function a_user_can_only_invite_someone_that_isnt_already_a_user()
    {
        $firstUser = create(User::class);
        $secondUser = create(User::class);
        $request = ['email' => $secondUser->email];

        $this->assertEquals(Invite::count(), 0);

        $response = $this->json('POST', 'api/users', $request, $this->getAuthHeader($firstUser))
            ->assertStatus(422);

        $this->assertEquals(Invite::count(), 0);
    }

    /** @test */
    public function invalid_invite_requests_will_throw_a_validation_error()
    {
        $user = create(User::class);
        $request = ['email' => 'some_invalid_string'];

        $response = $this->json('POST', 'api/users', $request, $this->getAuthHeader($user))
            ->assertStatus(422);
    }

    /** @test */
    public function unauthenticated_users_cannot_invite_other_users()
    {
        $request = ['email' => 'john@doe.com'];

        $response = $this->json('POST', 'api/users', $request)
            ->assertStatus(401);
    }

    protected function getAuthHeader($user)
    {
        $token = auth()->guard('api')->login($user);
        return ['Authorization' => 'Bearer ' . $token];
    }
}