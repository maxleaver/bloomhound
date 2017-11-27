<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProposalDiscountTest extends TestCase
{
    use RefreshDatabase;

    protected $discount;
    protected $proposal;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
        $this->discount = create('App\Discount', [
            'discountable_id' => $this->proposal->id,
            'discountable_type' => 'App\Proposal',
        ]);
        $this->request = [
            'name' => 'test discount',
            'type' => 'fixed',
            'amount' => 10,
        ];
    }

    /** @test */
    public function users_can_update_a_discount_on_a_proposal()
    {
        $this->assertNotEquals($this->request['name'], $this->discount->name);
        $this->assertNotEquals($this->request['type'], $this->discount->type);
        $this->assertNotEquals($this->request['amount'], $this->discount->amount);

        $this->updateDiscount($this->proposal->id, $this->discount->id, $this->request)
            ->assertStatus(200);

        $discount = $this->discount->fresh();
        $this->assertEquals($this->request['name'], $discount->name);
        $this->assertEquals($this->request['type'], $discount->type);
        $this->assertEquals($this->request['amount'], $discount->amount);
    }

    /** @test */
    public function a_discount_must_have_a_name()
    {
        $this->request['name'] = null;
        $this->updateDiscount($this->proposal->id, $this->discount->id, $this->request)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_discount_must_have_a_valid_type()
    {
        $this->request['type'] = 'bad type';
        $this->updateDiscount($this->proposal->id, $this->discount->id, $this->request)
            ->assertSessionHasErrors('type');
    }

    /** @test */
    public function a_discount_must_have_an_amount_greater_than_zero()
    {
        $this->request['amount'] = 0;
        $this->updateDiscount($this->proposal->id, $this->discount->id, $this->request)
            ->assertSessionHasErrors('amount');
    }

    /** @test */
    public function users_cannot_update_discounts_of_proposals_in_other_accounts()
    {
        $otherProposal = create('App\Proposal');
        $otherDiscount = create('App\Discount', [
            'discountable_id' => $otherProposal->id,
            'discountable_type' => 'App\Proposal',
        ]);

        $this->updateDiscount($otherProposal->id, $otherDiscount->id, $this->request)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_update_discounts_from_other_proposals()
    {
        $otherProposal = create('App\Proposal', [
            'event_id' => $this->proposal->event->id,
        ])->id;
        $this->updateDiscount($otherProposal, $this->discount->id, $this->request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_update_discounts_that_dont_exist()
    {
        $badId = 666;
        $this->updateDiscount($this->proposal->id, $badId, $this->request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_discounts()
    {
        $this->updateDiscount($this->proposal->id, $this->discount->id, $this->request, false, true)
            ->assertStatus(401);
    }

    protected function updateDiscount($proposalId, $discountId, $request, $signIn = true, $withJson = false)
    {
        $url = 'api/proposals/' . $proposalId . '/discounts/' . $discountId;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        if ($withJson) {
            return $this->patchJson($url, $request);
        }

        return $this->patch($url, $request);
    }
}
