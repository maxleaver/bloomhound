<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostProposalVendorsTest extends TestCase
{
    use RefreshDatabase;

    protected $proposal;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');

        $this->request = [
            'vendor_id' => create('App\Vendor', [
                'account_id' => $this->proposal->event->account->id,
            ])->id,
        ];
    }

    /** @test */
    public function user_can_add_an_existing_vendor_to_a_proposal()
    {
        $this->assertEquals($this->proposal->vendors()->count(), 0);

        $this->addVendor($this->proposal->id, $this->request)
            ->assertStatus(200);

        $this->assertEquals($this->proposal->vendors()->count(), 1);
    }

    /** @test */
    public function a_user_can_only_be_added_to_a_proposal_once()
    {
        $this->addVendor($this->proposal->id, $this->request)
            ->assertStatus(200);

        $this->addVendor($this->proposal->id, $this->request)
            ->assertSessionHasErrors('vendor_id');
    }

    /** @test */
    public function users_can_add_a_new_vendor_to_a_proposal()
    {
        $request = ['vendor_name' => 'My new vendor'];

        $this->assertEquals($this->proposal->vendors()->count(), 0);

        $this->addVendor($this->proposal->id, $request)
            ->assertStatus(200);

        $this->assertEquals($this->proposal->vendors()->count(), 1);
    }

    /** @test */
    public function users_cannot_add_vendors_from_other_accounts_to_proposals()
    {
        $request = ['vendor_id' => create('App\Vendor')->id];

        $this->addVendor($this->proposal->id, $request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_add_vendors_to_proposal_in_their_account()
    {
        $proposalInAnotherAccount = create('App\Proposal')->id;

        $this->addVendor($proposalInAnotherAccount, $this->request)
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_vendors_to_proposals()
    {
        $this->addVendor($this->proposal->id, $this->request, false, true)
            ->assertStatus(401);
    }

    protected function addVendor($id, $request, $signIn = true, $withJson = false)
    {
        $url = 'api/proposals/' . $id . '/vendors';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        if ($withJson) {
            return $this->postJson($url, $request);
        }

        return $this->post($url, $request);
    }
}
