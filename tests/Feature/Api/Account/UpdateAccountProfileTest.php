<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateAccountProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->account = create('App\Account');
        $this->request = make('App\Account')->toArray();
    }

    /** @test */
    public function a_user_can_update_their_account_profile()
    {
    	$this->updateProfile()
    		->assertStatus(200);

    	$account = $this->account->fresh();
    	$this->assertEquals($account->name, $this->request['name']);
    	$this->assertEquals($account->address, $this->request['address']);
    	$this->assertEquals($account->website, $this->request['website']);
    	$this->assertEquals($account->email, $this->request['email']);
    	$this->assertEquals($account->phone, $this->request['phone']);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_account_profiles()
    {
    	$this->updateProfile(false)
    		->assertStatus(401);
    }

    protected function updateProfile($signIn = true)
    {
        $url = 'api/account';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->account->id,
            ]));
        }

        return $this->patchJson($url, $this->request);
    }
}
