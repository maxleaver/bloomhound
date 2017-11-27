<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProposalSetupTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $setup;

    protected function setUp()
    {
        parent::setUp();

        $this->setup = create('App\Setup');
        $this->request = [
            'address' => 'new address',
            'description' => 'new description',
            'fee' => 55.00,
            'setup_on' => Carbon::now()->addWeek()->toRfc3339String(),
        ];
    }

    /** @test */
    public function users_can_update_an_event_setup()
    {
        $this->updateSetup($this->setup->id)
            ->assertStatus(200);

        $this->assertEquals($this->request['address'], $this->setup->fresh()->address);
        $this->assertEquals($this->request['description'], $this->setup->fresh()->description);
        $this->assertEquals($this->request['fee'], $this->setup->fresh()->fee);
        $this->assertEquals(
            $this->request['setup_on'],
            $this->setup->fresh()->setup_on->toRfc3339String()
        );
    }

    /** @test */
    public function users_can_only_update_event_setups_in_their_account()
    {
        $someOtherSetup = create('App\Setup')->id;
        $this->updateSetup($someOtherSetup)
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_only_update_event_setups_that_exist()
    {
        $badId = 666;
        $this->updateSetup($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_event_setups()
    {
        $this->updateSetup($this->setup->id, false)
            ->assertStatus(401);
    }

    protected function updateSetup($id, $signIn = true)
    {
        $url = 'api/setups/' . $id;
        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->setup->account->id,
            ]));
        }

        return $this->patchJson($url, $this->request);
    }
}
