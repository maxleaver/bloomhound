<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $url;
    protected $users;

    protected function setUp()
    {
        parent::setUp();

        $this->account = create('App\Account');
        $this->users = create('App\User', [
            'account_id' => $this->account->id
        ], 3);
        $this->url = 'api/users';
    }

    /** @test */
    public function a_user_can_get_a_list_of_users_on_their_account()
    {
        $someOtherAccount = create('App\Account');
        $notAccountUser = create('App\User', [
            'account_id' => $someOtherAccount->id
        ]);

        $this->signIn($this->users[0])
            ->getJson($this->url)
        	->assertStatus(200)
            ->assertJsonFragment([$this->users[0]->name])
            ->assertJsonFragment([$this->users[1]->name])
            ->assertJsonFragment([$this->users[2]->name])
            ->assertJsonMissing([$notAccountUser->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_request_users()
    {
        $this->getJson($this->url)
            ->assertStatus(401);
    }
}
