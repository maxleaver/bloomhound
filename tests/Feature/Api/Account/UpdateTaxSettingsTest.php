<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTaxSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->account = create('App\Account');
        $this->request = [
            'use_tax' => true,
            'tax_amount' => 10,
        ];
    }

    /** @test */
    public function a_user_can_update_their_account_tax_settings()
    {
        $this->account->settings->update([
            'use_tax' => false,
            'tax_amount' => 0
        ]);

        $this->assertFalse($this->account->settings->use_tax);
        $this->assertEquals($this->account->settings->tax_amount, 0);

        $this->updateSettings()
            ->assertStatus(200);

        $this->assertTrue($this->account->fresh()->settings->use_tax);
        $this->assertEquals($this->account->fresh()->settings->tax_amount, 10);
    }

    /** @test */
    public function a_tax_amount_must_be_greater_than_zero()
    {
        $this->request['tax_amount'] = 0;

        $this->updateSettings()
            ->assertSessionHasErrors('tax_amount');
    }

    /** @test */
    public function unauthenticated_users_cannot_update_tax_settings()
    {
        $this->updateSettings(false, true)
            ->assertStatus(401);
    }

    protected function updateSettings($signIn = true, $withJson = false)
    {
        $url = 'api/account/settings';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->account->id,
            ]));
        }

        if ($withJson) {
            return $this->patchJson($url, $this->request);
        }

        return $this->patch($url, $this->request);
    }
}
