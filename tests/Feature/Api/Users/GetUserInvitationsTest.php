<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserInvitationsTest extends TestCase
{
    use RefreshDatabase;

    protected $invites;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->invites = create('App\Invite', [
            'account_id' => $this->user->account->id
        ], 3);
        $this->url = 'api/invitations';
    }

    /** @test */
    public function authenticated_users_can_get_a_list_of_pending_user_invitations_for_their_account()
    {
        $someOtherAccount = create('App\Account');
        $notAccountInvite = create('App\Invite', [
            'account_id' => $someOtherAccount->id
        ]);

        $this->signIn($this->user)
            ->getJson($this->url)
            ->assertStatus(200)
            ->assertJsonFragment([$this->invites[0]->email])
            ->assertJsonFragment([$this->invites[1]->email])
            ->assertJsonFragment([$this->invites[2]->email])
            ->assertJsonMissing([$notAccountInvite->email]);
    }

    /** @test */
    public function unauthenticated_users_cant_get_a_list_of_user_invitations()
    {
        $this->getJson($this->url)
            ->assertStatus(401);
    }
}
