<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEventSetupTest extends TestCase
{
	use RefreshDatabase;

    protected $setup;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->setup = create('App\Setup');
        $this->user = create('App\User', [
            'account_id' => $this->setup->account->id
        ]);
    }

    protected function url($id)
    {
        return 'api/setups/' . $id;
    }

    /** @test */
    public function users_can_update_an_event_setup()
    {
        $address = 'new address';
        $description = 'new description';
        $fee = 55.00;
        $setup_on = Carbon::now()->addWeek();
        $request = [
            'address' => $address,
            'description' => $description,
            'fee' => $fee,
            'setup_on' => $setup_on->toRfc3339String(),
        ];

        $this->signIn($this->user)
            ->patchJson($this->url($this->setup->id), $request)
            ->assertStatus(200);

        $this->assertEquals($this->setup->fresh()->address, $address);
        $this->assertEquals($this->setup->fresh()->description, $description);
        $this->assertEquals($this->setup->fresh()->fee, $fee);
        $this->assertEquals(
            $setup_on->toRfc3339String(),
            $this->setup->fresh()->setup_on->toRfc3339String()
        );
    }

    /** @test */
    public function users_can_only_update_event_setups_in_their_account()
    {
        $someOtherSetup = create('App\Setup');
        $request = ['address' => 'some address'];

        $this->signIn($this->user)
            ->patchJson($this->url($someOtherSetup->id), $request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_update_event_setups_that_exist()
    {
        $someBadId = 666;
        $request = ['address' => 'some address'];

        $this->signIn($this->user)
            ->patchJson($this->url($someBadId), $request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_event_setups()
    {
        $request = ['address' => 'some address'];
        $this->patchJson($this->url($this->setup->id), $request)
            ->assertStatus(401);
    }
}
