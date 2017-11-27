<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateVendorTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $vendor;

    protected function setUp()
    {
        parent::setUp();

        $this->vendor = create('App\Vendor');
        $this->request = [
            'name' => 'new name',
            'email' => 'email@test.com',
            'address' => '123 Fake Street, Town, ND USA',
            'phone' => '(555) 555-5555',
            'website' => 'www.website.com',
        ];
    }

    /** @test */
    public function users_can_update_a_vendors_profile()
    {
        $this->updateVendor($this->vendor->id)
            ->assertStatus(200);

        $vendor = $this->vendor->fresh();
        $this->assertEquals($this->request['name'], $vendor->name);
        $this->assertEquals($this->request['email'], $vendor->email);
        $this->assertEquals($this->request['address'], $vendor->address);
        $this->assertEquals($this->request['phone'], $vendor->phone);
        $this->assertEquals($this->request['website'], $vendor->website);
    }

    /** @test */
    public function users_can_only_update_vendors_in_their_account()
    {
        $vendorInAnotherAccount = create('App\Vendor')->id;
        $this->updateVendor($vendorInAnotherAccount)
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_only_update_vendors_that_exist()
    {
        $badId = 123;
        $this->updateVendor($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_vendor_profiles()
    {
        $this->updateVendor($this->vendor->id, false)
            ->assertStatus(401);
    }

    protected function updateVendor($id, $signIn = true)
    {
        $url = 'api/vendors/' . $id;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->vendor->account->id,
            ]));
        }

        return $this->patchJson($url, $this->request);
    }
}
