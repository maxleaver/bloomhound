<?php

namespace Tests\Api\Feature;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_see_other_users_on_their_account()
    {
        $this->withoutExceptionHandling();

        $account = create('App\Account');
        $accountUsers = create('App\User', [
            'account_id' => $account->id
        ], 3);

        $someOtherAccount = create('App\Account');
        $notAccountUser = create('App\User', [
            'account_id' => $someOtherAccount->id
        ]);

        Passport::actingAs($accountUsers[0]);
        $response = $this->get('api/users')
        	->assertStatus(200)
            ->assertJsonFragment([$accountUsers[0]->name])
            ->assertJsonFragment([$accountUsers[1]->name])
            ->assertJsonFragment([$accountUsers[2]->name])
            ->assertJsonMissing([$notAccountUser->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_request_users()
    {
        $account = create('App\Account');
        $accountUsers = create('App\User', [
            'account_id' => $account->id
        ], 3);

        $response = $this->json('GET', 'api/users')
            ->assertStatus(401);
    }
}