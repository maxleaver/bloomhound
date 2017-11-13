<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteArrangementDiscountTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangement;
    protected $discount;

    protected function setUp()
    {
        parent::setUp();

        $this->arrangement = create('App\Arrangement');
        $this->discount = create('App\Discount', [
            'discountable_id' => $this->arrangement->id,
            'discountable_type' => 'App\Arrangement'
        ]);
    }

    /** @test */
    public function a_user_can_delete_an_arrangement_discount()
    {
        $this->assertEquals(1, $this->arrangement->discounts()->count());

        $this->deleteDiscount()
            ->assertStatus(200);

        $this->assertEquals(0, $this->arrangement->discounts()->count());
    }

    /** @test */
    public function a_user_can_only_delete_discounts_from_arrangements_in_their_account()
    {
        $anotherArrangement = create('App\Arrangement');
        $discount = create('App\Discount', [
            'discountable_id' => $anotherArrangement->id,
            'discountable_type' => 'App\Arrangement'
        ]);
        $this->deleteDiscount($anotherArrangement->id, $discount->id)
            ->assertStatus(404);
    }

    /** @test */
    public function a_user_can_only_delete_valid_discounts()
    {
        $badDiscount = 666;
        $this->deleteDiscount($this->arrangement->id, $badDiscount)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_discounts()
    {
        $this->deleteDiscount($this->arrangement->id, $this->discount->id, false)
            ->assertStatus(401);
    }

    protected function url($arrangement, $discount)
    {
        return 'api/arrangements/' . $arrangement . '/discounts/' . $discount;
    }

    protected function deleteDiscount($arrangement = null, $id = null, $signIn = true)
    {
        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->arrangement->account->id,
            ]));
        }

        if (is_null($arrangement)) {
            $arrangement = $this->arrangement->id;
        }

        if (is_null($id)) {
            $id = $this->discount->id;
        }

        return $this->deleteJson($this->url($arrangement, $id));
    }
}
