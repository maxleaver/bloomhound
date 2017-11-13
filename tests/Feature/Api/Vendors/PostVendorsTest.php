<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostVendorsTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->account = create('App\Account');
        $this->request = ['name' => 'Vendor Name'];
    }

    /** @test */
    public function a_user_can_add_a_vendor()
    {
        $this->assertEquals($this->account->vendors()->count(), 0);

        $this->createVendor()
    		->assertStatus(200)
    		->assertJsonFragment([$this->request['name']]);

    	$this->assertEquals($this->account->vendors()->count(), 1);
    }

    /** @test */
    public function a_vendor_requires_a_name()
    {
        $this->request['name'] = null;

        $this->createVendor()
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function unauthenticated_users_cannot_add_vendors()
    {
    	$this->createVendor(false, true)
    		->assertStatus(401);
    }

    protected function createVendor($signIn = true, $withJson = false)
    {
        $url = 'api/vendors';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->account->id,
            ]));
        }

        if ($withJson) {
            return $this->postJson($url, $this->request);
        }

        return $this->post($url, $this->request);
    }
}
