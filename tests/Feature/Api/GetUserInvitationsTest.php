<?php

namespace Tests\Api\Feature;

use App\Invite;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserInvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_users_can_get_a_list_of_pending_user_invitations_for_their_account()
    {
        $user = create(User::class);
        $someOtherAccount = create('App\Account');

        $accountInvites = create(Invite::class, [
            'account_id' => $user->account->id
        ], 3);

        $notAccountInvite = create(Invite::class, [
            'account_id' => $someOtherAccount->id
        ]);

        Passport::actingAs($user, ['api/invitations']);

        $response = $this->json('GET', 'api/invitations')
            ->assertStatus(200)
            ->assertJsonFragment([$accountInvites[0]->email])
            ->assertJsonMissing([$notAccountInvite->email]);
    }

    /** @test */
    public function unauthenticated_users_cant_get_a_list_of_user_invitations()
    {
        $response = $this->json('GET', 'api/invitations')
            ->assertStatus(401);
    }
}