<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteProposalVendorsTest extends TestCase
{
    use RefreshDatabase;

    protected $proposal;
    protected $vendor;

    protected function setUp()
    {
        parent::setUp();

        $this->vendor = create('App\Vendor');
        $this->proposal = create('App\Proposal', [
            'event_id' => create('App\Event', [
                'account_id' => $this->vendor->account->id,
            ]),
        ]);

        $this->proposal->vendors()->attach($this->vendor);
    }

    /** @test */
    public function a_user_can_remove_a_vendor_from_a_proposal()
    {
        $this->assertEquals($this->proposal->vendors()->count(), 1);

        $this->deleteVendor($this->proposal->id, $this->vendor->id)
            ->assertStatus(200);

        $this->assertEquals($this->proposal->fresh()->vendors()->count(), 0);
    }

    /** @test */
    public function users_cannot_remove_vendors_from_proposals_in_other_accounts()
    {
        $someOtherProposal = create('App\Proposal');
        $vendor = create('App\Vendor');
        $someOtherProposal->vendors()->attach($vendor);

        $this->deleteVendor($someOtherProposal->id, $vendor->id)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_remove_vendors_that_are_not_attached_to_the_proposal()
    {
        $unattachedVendor = create('App\Vendor', [
            'account_id' => $this->vendor->account->id
        ]);

        $this->deleteVendor($this->proposal->id, $unattachedVendor->id)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_remove_vendors_from_a_proposal_that_doesnt_exist()
    {
        $badProposalId = 123;

        $this->deleteVendor($badProposalId, $this->vendor->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_remove_vendors_from_proposals()
    {
        $this->deleteVendor($this->proposal->id, $this->vendor->id, false)
            ->assertStatus(401);
    }

    protected function deleteVendor($proposalId, $vendorId, $signIn = true)
    {
        $url = 'api/proposals/' . $proposalId . '/vendors/' . $vendorId;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->vendor->account->id,
            ]));
        }

        return $this->deleteJson($url);
    }
}
