<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostProposalDiscountTest extends TestCase
{
    use RefreshDatabase;

    protected $proposal;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
        $this->request = [
        	'name' => 'test discount',
        	'type' => 'fixed',
        	'amount' => 10,
        ];
    }

    /** @test */
    public function a_user_can_add_a_discount_to_a_proposal()
    {
    	$this->assertEquals(0, $this->proposal->discounts->count());

    	$this->postDiscount($this->proposal->id, $this->request)
    		->assertStatus(200)
    		->getData();

    	$proposal = $this->proposal->fresh();
    	$discount = $proposal->discounts->first();
    	$this->assertEquals(1, $proposal->discounts->count());
    	$this->assertEquals($this->request['name'], $discount->name);
    	$this->assertEquals($this->request['type'], $discount->type);
    	$this->assertEquals($this->request['amount'], $discount->amount);
    }

    /** @test */
    public function a_discount_must_have_a_name()
    {
    	$this->request['name'] = null;
    	$this->postDiscount($this->proposal->id, $this->request)
    		->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_discount_must_have_a_valid_type()
    {
    	$this->request['type'] = 'bad type';
    	$this->postDiscount($this->proposal->id, $this->request)
    		->assertSessionHasErrors('type');
    }

    /** @test */
    public function a_discount_must_have_an_amount_greater_than_zero()
    {
    	$this->request['amount'] = 0;
    	$this->postDiscount($this->proposal->id, $this->request)
    		->assertSessionHasErrors('amount');
    }

    /** @test */
    public function users_cannot_add_discounts_to_proposals_in_other_accounts()
    {
    	$otherProposal = create('App\Proposal')->id;
    	$this->postDiscount($otherProposal, $this->request)
    		->assertStatus(404);
    }

    /** @test */
    public function users_cannot_add_discounts_to_invalid_proposals()
    {
    	$badId = 666;
    	$this->postDiscount($badId, $this->request)
    		->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_discounts()
    {
    	$this->postDiscount($this->proposal->id, $this->request, false, true)
    		->assertStatus(401);
    }

    protected function postDiscount($id, $request, $signIn = true, $withJson = false)
    {
    	$url = 'api/proposals/' . $id . '/discounts';

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
