<?php

namespace Tests\Feature\Api;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetProfileTest extends TestCase
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
    }

    /** @test */
    public function a_user_can_get_their_profile()
    {
        Passport::actingAs($this->user);
    	$response = $this->json('GET', $this->url)
    		->assertStatus(200)
    		->assertJsonFragment([$this->user->name])
    		->assertJsonFragment([$this->user->email]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_a_profile()
    {
    	$response = $this->json('GET', $this->url)
    		->assertStatus(401);
    }
}
