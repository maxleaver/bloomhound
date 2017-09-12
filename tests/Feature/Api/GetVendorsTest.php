<?php

namespace Tests\Feature\Api;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetVendorsTest extends TestCase
{
    use RefreshDatabase;

    protected $vendors;
    protected $otherVendors;
    protected $user;
    protected $url;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->vendors = create('App\Vendor', ['account_id' => $this->user->account->id], 3);
        $this->otherVendors = create('App\Vendor', [], 3);
        $this->url = 'api/vendors';
    }

    /** @test */
    public function a_user_can_get_a_list_of_vendors_for_their_account()
    {
    	$this->withoutExceptionHandling();

        Passport::actingAs($this->user, [$this->url]);

        $response = $this->json('GET', $this->url)
    		->assertStatus(200)
    		->assertJsonFragment([$this->vendors[0]->name])
    		->assertJsonFragment([$this->vendors[1]->name])
    		->assertJsonFragment([$this->vendors[2]->name])
            ->assertJsonMissing([$this->otherVendors[0]->name]);
    }

    /** @test */
    public function unauthorized_users_cannot_get_vendors()
    {
    	$response = $this->json('GET', $this->url)
    		->assertStatus(401);
    }
}
