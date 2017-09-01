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

        $account = factory(Account::class)->create();
        $accountUsers = factory(User::class, 3)->create([
            'account_id' => $account->id
        ]);

        $someOtherAccount = factory(Account::class)->create();
        $notAccountUser = factory(User::class)->create([
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
}