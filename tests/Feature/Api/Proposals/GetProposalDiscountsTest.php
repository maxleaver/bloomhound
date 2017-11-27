<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetProposalDiscountsTest extends TestCase
{
    use RefreshDatabase;

    protected $discounts;
    protected $proposal;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
        $this->discounts = create('App\Discount', [
            'discountable_id' => $this->proposal->id,
            'discountable_type' => 'App\Proposal',
        ], 2);
    }

    /** @test */
    public function a_user_can_get_a_list_of_discounts_for_a_proposal()
    {
        $anotherProposal = create('App\Proposal', [
            'event_id' => $this->proposal->event->id,
        ]);
        $otherDiscounts = create('App\Discount', [
            'discountable_id' => $anotherProposal->id,
            'discountable_type' => 'App\Proposal',
        ], 2);

        $response = $this->getDiscounts($this->proposal->id)
            ->assertStatus(200)
            ->getData();

        $this->assertEquals(2, count($response));
        $this->assertEquals($this->discounts[0]->amount, $response[0]->amount);
        $this->assertEquals($this->discounts[1]->amount, $response[1]->amount);
    }

    /** @test */
    public function users_cannot_get_discounts_for_proposals_in_other_accounts()
    {
        $otherProposal = create('App\Proposal')->id;
        $this->getDiscounts($otherProposal)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_get_discounts_for_invalid_proposals()
    {
        $badId = 666;
        $this->getDiscounts($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_discounts_for_proposals()
    {
        $this->getDiscounts($this->proposal->id, false)
            ->assertStatus(401);
    }

    protected function getDiscounts($id, $signIn = true)
    {
        $url = 'api/proposals/' . $id . '/discounts';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
