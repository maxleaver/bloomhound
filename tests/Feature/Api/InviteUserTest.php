<?php

namespace Tests\Api\Feature;

use App\Invite;
use App\Mail\UserInvited;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class InviteUserTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $user;
    protected $url;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->url = 'api/users';
        $this->request = ['email' => 'john@doe.com'];
    }

    /** @test */
    public function a_user_can_invite_other_users()
    {
        Mail::fake();

        $this->assertEquals(Invite::count(), 0);

        $this->signIn($this->user)
            ->postJson($this->url, $this->request)
        	->assertStatus(200);

        $this->assertEquals(Invite::count(), 1);

        Mail::assertSent(UserInvited::class, function ($mail) {
            return $mail->hasTo($this->request['email']);
        });
    }

    /** @test */
    public function a_user_can_only_invite_someone_that_isnt_already_a_user()
    {
        $anotherUser = create('App\User');
        $this->request['email'] = $anotherUser->email;

        $this->assertEquals(Invite::count(), 0);

        $this->signIn($this->user)
            ->postJson($this->url, $this->request)
            ->assertStatus(422);

        $this->assertEquals(Invite::count(), 0);
    }

    /** @test */
    public function invalid_invite_requests_will_throw_a_validation_error()
    {
        $this->request['email'] = 'some invalid string';

        $this->signIn($this->user)
            ->postJson($this->url, $this->request)
            ->assertStatus(422);
    }

    /** @test */
    public function unauthenticated_users_cannot_invite_other_users()
    {
        $this->postJson($this->url, $this->request)
            ->assertStatus(401);
    }
}
