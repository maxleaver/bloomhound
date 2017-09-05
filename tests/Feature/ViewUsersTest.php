<?php

namespace Tests\Feature;

use App\Account;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_see_other_users_on_their_account()
    {
        $this->withoutExceptionHandling();

        $account = create(Account::class);
        $accountUsers = create(User::class, [
            'account_id' => $account->id
        ], 3);

        $someOtherAccount = create(Account::class);
        $notAccountUser = create(User::class, [
            'account_id' => $someOtherAccount->id
        ]);

        $token = auth()->guard('api')->login($accountUsers[0]);

        $headers['Authorization'] = 'Bearer ' . $token;

        $response = $this->get('api/users', [], $headers)
        	->assertStatus(200)
            ->assertJsonFragment([$accountUsers[0]->name])
            ->assertJsonFragment([$accountUsers[1]->name])
            ->assertJsonFragment([$accountUsers[2]->name])
            ->assertJsonMissing([$notAccountUser->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_request_users()
    {
        $account = create(Account::class);
        $accountUsers = create(User::class, [
            'account_id' => $account->id
        ], 3);

        $response = $this->json('GET', 'api/users')
            ->assertStatus(401);
    }
}