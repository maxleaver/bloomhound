<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProposalDeliveryTest extends TestCase
{
	use RefreshDatabase;

    protected $delivery;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->delivery = create('App\Delivery');
        $this->user = create('App\User', [
            'account_id' => $this->delivery->account->id
        ]);
    }

    protected function url($id)
    {
        return 'api/deliveries/' . $id;
    }

    /** @test */
    public function users_can_update_a_delivery()
    {
        $address = 'new address';
        $description = 'new description';
        $fee = 55.00;
        $deliver_on = Carbon::now()->addWeek();
        $request = [
            'address' => $address,
            'description' => $description,
            'fee' => $fee,
            'deliver_on' => $deliver_on->toRfc3339String(),
        ];

        $this->signIn($this->user)
            ->patchJson($this->url($this->delivery->id), $request)
            ->assertStatus(200);

        $this->assertEquals($this->delivery->fresh()->address, $address);
        $this->assertEquals($this->delivery->fresh()->description, $description);
        $this->assertEquals($this->delivery->fresh()->fee, $fee);
        $this->assertEquals(
            $deliver_on->toRfc3339String(),
            $this->delivery->fresh()->deliver_on->toRfc3339String()
        );
    }

    /** @test */
    public function users_can_only_update_deliveries_in_their_account()
    {
        $someOtherDelivery = create('App\Delivery');
        $request = ['address' => 'some address'];

        $this->signIn($this->user)
            ->patchJson($this->url($someOtherDelivery->id), $request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_update_deliveries_that_exist()
    {
        $someBadId = 666;
        $request = ['address' => 'some address'];

        $this->signIn($this->user)
            ->patchJson($this->url($someBadId), $request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_deliveries()
    {
        $request = ['address' => 'some address'];
        $this->patchJson($this->url($this->delivery->id), $request)
            ->assertStatus(401);
    }
}
