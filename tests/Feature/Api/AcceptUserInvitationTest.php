<?php

namespace Tests\Api\Feature;

use App\Invite;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// class AcceptUserInvitationTest extends TestCase
// {
// 	use RefreshDatabase;

//     /** @test */
//     public function an_invitee_can_accept_a_user_invitation()
//     {
//     	$this->withoutExceptionHandling();

//     	$invite = create(Invite::class);
//     	$request = [
//     		'name' => 'Jane Doe',
//     		'password' => 'abc123'
//     	];

//     	$this->assertEquals(Invite::count(), 1);

//     	$response = $this->json('POST', 'api/invitation/accept/' . $invite->token, $request)
//     		->assertStatus(200)
//     		->assertJsonStructure(['token']);

//     	$user = User::whereEmail($invite->email)->first();

//     	$this->assertNotNull($user);
//     	$this->assertEquals(Invite::count(), 0);
//     }

//     /** @test */
//     public function invalid_tokens_in_an_invite_url_returns_an_error()
//     {
//     	$badToken = 'some_bad_string';
//     	$response = $this->json('POST', 'api/invitation/accept/' . $badToken)
//     		->assertStatus(404);
//     }
// }
