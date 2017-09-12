<?php

namespace Tests\Feature\Api;

use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostVendorNotesTest extends TestCase
{
    use RefreshDatabase;

    protected $vendor;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->request = ['text' => 'This is my note'];
        $this->user = create('App\User');
        $this->vendor = create('App\Vendor', ['account_id' => $this->user->account->id]);
    }

    /** @test */
    public function a_user_can_add_notes_to_a_vendor()
    {
    	$this->assertEquals($this->vendor->notes()->count(), 0);

        Passport::actingAs($this->user);
        $response = $this->json('POST', $this->getUrl($this->vendor->id), $this->request)
    		->assertStatus(200);

        $this->assertEquals($this->vendor->notes()->count(), 1);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_vendors_assigned_to_their_account()
    {
    	$vendor = create('App\Vendor');

        Passport::actingAs($this->user);
        $response = $this->json('POST', $this->getUrl($vendor->id), $this->request)
    		->assertStatus(404);
    }

    /** @test */
    public function a_user_can_only_add_notes_to_vendors_that_exist()
    {
        Passport::actingAs($this->user);
        $response = $this->json('POST', $this->getUrl(123), $this->request)
    		->assertStatus(404);
    }

    protected function getUrl($id)
    {
        return '/api/vendors/' . $id . '/notes';
    }
}
