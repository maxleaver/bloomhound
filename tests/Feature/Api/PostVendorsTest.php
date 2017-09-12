<?php

namespace Tests\Api\Feature;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostVendorsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $url;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->url = 'api/vendors';
    }

    /** @test */
    public function a_user_can_add_a_vendor()
    {
        $this->assertEquals($this->user->account->vendors()->count(), 0);

        Passport::actingAs($this->user);
    	$response = $this->json('POST', $this->url, ['name' => 'Vendor Name'])
    		->assertStatus(200)
    		->assertJsonFragment(['Vendor Name']);

    	$this->assertEquals($this->user->account->vendors()->count(), 1);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_vendors()
    {
    	$response = $this->json('POST', $this->url, ['name' => 'Vendor Name'])
    		->assertStatus(401);
    }
}