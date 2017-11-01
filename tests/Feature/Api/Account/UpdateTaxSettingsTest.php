<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTaxSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $request;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->request = [
            'use_tax' => true,
            'tax_amount' => 10,
        ];
        $this->url = 'api/account/settings';
        $this->user = create('App\User');
        $this->account = $this->user->account;
    }

    /** @test */
    public function a_user_can_update_their_account_tax_settings()
    {
        $this->account->settings->use_tax = false;
        $this->account->settings->tax_amount = 0;
        $this->account->settings->save();

        $this->assertFalse($this->account->settings->use_tax);
        $this->assertEquals($this->account->settings->tax_amount, 0);

        $this->signIn($this->user)
            ->patchJson($this->url, $this->request)
            ->assertStatus(200);

        $this->assertTrue($this->account->settings->use_tax);
        $this->assertEquals($this->account->settings->tax_amount, 10);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_tax_settings()
    {
        $this->patchJson($this->url, $this->request)
            ->assertStatus(401);
    }
}
