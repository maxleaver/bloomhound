<?php

namespace Tests\Feature\Api;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProfileTest extends TestCase
{
	use RefreshDatabase;

    protected $request;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->url = '/api/profile';
        $this->request = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
        ];
    }

    /** @test */
    public function a_user_can_update_their_profile()
    {
    	$this->signIn($this->user)
            ->patchJson($this->url, $this->request)
    		->assertStatus(200);

        $this->assertEquals($this->user->fresh()->name, $this->request['name']);
        $this->assertEquals($this->user->fresh()->email, $this->request['email']);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_a_profile()
    {
    	$this->patchJson($this->url, $this->request)
    		->assertStatus(401);
    }
}
