<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostArrangementDiscountTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangement;

    protected function setUp()
    {
        parent::setUp();

        $this->arrangement = create('App\Arrangement', ['quantity' => 1]);

        factory('App\ArrangementIngredient')->states('item')->create([
            'arrangement_id' => $this->arrangement->id,
            'arrangeable_id' => factory('App\Item')
                ->states('cost')
                ->create(['cost' => 50])
                ->id,
            'quantity' => 1,
        ]);
    }

    /** @test */
    public function a_discount_requires_a_name()
    {
        $this->createDiscount(['name' => null])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_discount_requires_a_type()
    {
        $this->createDiscount(['type' => null])
            ->assertSessionHasErrors('type');
    }

    /** @test */
    public function a_discount_requires_a_valid_type()
    {
        $this->createDiscount(['type' => 'invalid type'])
            ->assertSessionHasErrors('type');
    }

    /** @test */
    public function a_discount_requires_an_amount()
    {
        $this->createDiscount(['amount' => null])
            ->assertSessionHasErrors('amount');
    }

    /** @test */
    public function a_discount_amount_must_be_a_number()
    {
        $this->createDiscount(['amount' => 'invalid amount'])
            ->assertSessionHasErrors('amount');
    }

    /** @test */
    public function a_discount_amount_must_be_greater_than_zero()
    {
        $this->createDiscount(['amount' => 0])
            ->assertSessionHasErrors('amount');
    }

    /** @test */
    public function a_user_can_create_a_discount_for_an_arrangement()
    {
        $discount = make('App\Discount', ['amount' => 10]);

        $this->createDiscount($discount->toArray())
            ->assertStatus(200);

        $result = $this->arrangement->discounts()->first();
        $this->assertNotNull($result);
        $this->assertEquals($result->name, $discount->name);
        $this->assertEquals($result->type, $discount->type);
        $this->assertEquals($result->amount, $discount->amount);
    }

    /** @test */
    public function a_proper_discount_should_return_the_total_price_for_its_arrangement()
    {
        $this->createDiscount()
            ->assertStatus(200)
            ->assertJsonFragment([
                'total_price' => $this->arrangement->fresh()->total_price,
            ]);
    }

    /** @test */
    public function a_discount_cannot_be_greater_than_the_arrangement_price()
    {
        $discount = factory('App\Discount')
            ->states('fixed')
            ->make(['amount' => 100])
            ->toArray();

        $this->assertEquals($this->arrangement->total_price, 50);

        $this->createDiscount($discount)
            ->assertSessionHasErrors('amount');
    }

    /** @test */
    public function a_user_cannot_create_discounts_for_arrangements_in_other_accounts()
    {
        $someOtherArrangement = create('App\Arrangement')->id;
        $this->createDiscount([], $someOtherArrangement)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_cannot_create_discounts_for_invalid_arrangements()
    {
        $badArrangementId = 666;
        $this->createDiscount([], $badArrangementId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_create_discounts()
    {
        $discount = make('App\Discount')->toArray();
        $this->postJson($this->url($this->arrangement->id), $discount)
            ->assertStatus(401);
    }

    protected function url($id)
    {
        return 'api/arrangements/' . $id . '/discounts';
    }

    protected function createDiscount($overrides = [], $id = null)
    {
        $discount = make('App\Discount', $overrides)->toArray();
        $user = create('App\User', [
            'account_id' => $this->arrangement->account->id,
        ]);

        if (is_null($id)) {
            $id = $this->arrangement->id;
        }

        return $this->signIn($user)
            ->post($this->url($id), $discount);
    }
}
