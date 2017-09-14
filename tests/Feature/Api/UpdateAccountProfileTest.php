<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateAccountProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->url = 'api/account';
        $this->request = make('App\Account')->toArray();
    }

    /** @test */
    public function a_user_can_update_their_account_profile()
    {
    	$response = $this->signIn($this->user)
            ->patchJson($this->url, $this->request)
    		->assertStatus(200);

    	$account = $this->user->account->fresh();
    	$this->assertEquals($account->name, $this->request['name']);
    	$this->assertEquals($account->address, $this->request['address']);
    	$this->assertEquals($account->website, $this->request['website']);
    	$this->assertEquals($account->email, $this->request['email']);
    	$this->assertEquals($account->phone, $this->request['phone']);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_account_profiles()
    {
    	$response = $this->patchJson($this->url, $this->request)
    		->assertStatus(401);
    }
}
