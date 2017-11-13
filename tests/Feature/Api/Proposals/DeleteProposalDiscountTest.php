<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteProposalDiscountTest extends TestCase
{
    use RefreshDatabase;

    protected $discount;
    protected $proposal;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
        $this->discount = factory('App\Discount')
            ->states('proposal')
            ->create([
                'discountable_id' => $this->proposal->id,
            ]);
    }

    /** @test */
    public function users_can_delete_a_discount_from_a_proposal()
    {
        $this->assertEquals(1, $this->proposal->discounts->count());

        $this->deleteDiscount($this->proposal->id, $this->discount->id)
            ->assertStatus(200);

        $proposal = $this->proposal->fresh();
        $this->assertEquals(0, $proposal->discounts->count());
    }

    /** @test */
    public function users_cannot_delete_discounts_from_proposals_in_other_accounts()
    {
        $otherProposal = create('App\Proposal')->id;
        $discount = factory('App\Discount')
            ->states('proposal')
            ->create(['discountable_id' => $otherProposal])
            ->id;

        $this->deleteDiscount($otherProposal, $discount)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_delete_discounts_that_arent_in_the_proposal()
    {
        $discount = factory('App\Discount')
            ->states('proposal')
            ->create()
            ->id;

        $this->deleteDiscount($this->proposal->id, $discount)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_discounts()
    {
        $this->deleteDiscount($this->proposal->id, $this->discount->id, false)
            ->assertStatus(401);
    }

    protected function deleteDiscount($proposalId, $discountId, $signIn = true)
    {
        $url = 'api/proposals/' . $proposalId . '/discounts/' . $discountId;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        return $this->deleteJson($url);
    }
}
