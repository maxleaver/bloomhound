<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateVendorTest extends TestCase
{
	use RefreshDatabase;

    protected $user;
    protected $vendor;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->vendor = create('App\Vendor', ['account_id' => $this->user->account->id]);
    }

    protected function url($id)
    {
        return 'api/vendors/' . $id;
    }

    /** @test */
    public function users_can_update_a_vendors_profile()
    {
        $name = 'new name';
        $email = 'email@test.com';
        $address = '123 Fake Street, Town, ND USA';
        $phone = '(555) 555-5555';
        $website = 'www.website.com';
        $request = compact('name', 'email', 'address', 'phone', 'website');

        $this->signIn($this->user)
            ->patchJson($this->url($this->vendor->id), $request)
            ->assertStatus(200);

        $vendor = $this->vendor->fresh();
        $this->assertEquals($name, $vendor->name);
        $this->assertEquals($email, $vendor->email);
        $this->assertEquals($address, $vendor->address);
        $this->assertEquals($phone, $vendor->phone);
        $this->assertEquals($website, $vendor->website);
    }

    /** @test */
    public function users_can_only_update_vendors_in_their_account()
    {
        $vendorInAnotherAccount = create('App\Vendor');

        $this->signIn($this->user)
            ->patchJson($this->url($vendorInAnotherAccount->id), ['name' => 'a name'])
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_update_vendors_that_exist()
    {
        $badId = 123;

        $this->signIn($this->user)
            ->patchJson($this->url($badId), ['name' => 'a name'])
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_vendor_profiles()
    {
        $this->patchJson($this->url($this->vendor->id), ['name' => 'a name'])
            ->assertStatus(401);
    }
}
