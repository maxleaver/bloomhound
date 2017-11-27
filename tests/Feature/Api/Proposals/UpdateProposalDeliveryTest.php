<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProposalDeliveryTest extends TestCase
{
    use RefreshDatabase;

    protected $delivery;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->delivery = create('App\Delivery');
        $this->request = [
            'address' => 'new address',
            'description' => 'new description',
            'fee' => 55.00,
            'deliver_on' => Carbon::now()->addWeek()->toRfc3339String(),
        ];
    }

    /** @test */
    public function users_can_update_a_delivery()
    {
        $this->updateDelivery($this->delivery->id)
            ->assertStatus(200);

        $this->assertEquals($this->request['address'], $this->delivery->fresh()->address);
        $this->assertEquals($this->request['description'], $this->delivery->fresh()->description);
        $this->assertEquals($this->request['fee'], $this->delivery->fresh()->fee);
        $this->assertEquals(
            $this->request['deliver_on'],
            $this->delivery->fresh()->deliver_on->toRfc3339String()
        );
    }

    /** @test */
    public function users_can_only_update_deliveries_in_their_account()
    {
        $someOtherDelivery = create('App\Delivery')->id;
        $this->updateDelivery($someOtherDelivery)
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_only_update_deliveries_that_exist()
    {
        $badId = 666;
        $this->updateDelivery($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_deliveries()
    {
        $this->updateDelivery($this->delivery->id, false)
            ->assertStatus(401);
    }

    protected function updateDelivery($id, $signIn = true)
    {
        $url = 'api/deliveries/' . $id;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->delivery->account->id,
            ]));
        }

        return $this->patchJson($url, $this->request);
    }
}
