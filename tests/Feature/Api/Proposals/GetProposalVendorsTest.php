<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetProposalVendorsTest extends TestCase
{
    use RefreshDatabase;

    protected $proposal;
    protected $vendors;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
        $this->vendors = create('App\Vendor', [
            'account_id' => $this->proposal->event->account->id
        ], 3);
        $this->proposal->vendors()->attach($this->vendors);
    }

    /** @test */
    public function users_can_get_vendors_assigned_to_a_proposal()
    {
        $unassignedVendor = create('App\Vendor', [
            'account_id' => $this->proposal->event->account->id
        ]);
        $inAnotherAccount = create('App\Vendor');

        $this->getProposals($this->proposal->id)
            ->assertStatus(200)
            ->assertJsonFragment([$this->vendors[0]->name])
            ->assertJsonFragment([$this->vendors[1]->name])
            ->assertJsonFragment([$this->vendors[2]->name])
            ->assertJsonMissing([$unassignedVendor->name])
            ->assertJsonMissing([$inAnotherAccount->name]);
    }

    /** @test */
    public function users_cannot_get_vendors_for_proposals_in_other_accounts()
    {
        $otherProposal = create('App\Proposal')->id;

        $this->getProposals($otherProposal)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthorized_users_cannot_get_vendors_for_a_proposal()
    {
        $this->getProposals($this->proposal->id, false)
            ->assertStatus(401);
    }

    protected function getProposals($id, $signIn = true)
    {
        $url = 'api/proposals/' . $id . '/vendors';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
