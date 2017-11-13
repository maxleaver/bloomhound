<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetArrangeableTypeSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $settings;

    protected function setUp()
    {
        parent::setUp();

        $account = create('App\Account');
        $this->settings = create('App\ArrangeableTypeSetting', [
            'account_id' => $account->id,
        ], 3);
    }

    /** @test */
    public function users_can_get_arrangeable_type_settings()
    {
        $otherSetting = create('App\ArrangeableTypeSetting');

        $this->getSettings()
            ->assertStatus(200)
            ->assertJsonFragment([$this->settings[0]->type->name])
            ->assertJsonFragment([$this->settings[1]->type->name])
            ->assertJsonFragment([$this->settings[2]->type->name])
            ->assertJsonMissing([$otherSetting->type->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_arrangeable_type_settings()
    {
        $this->getSettings(false)
            ->assertStatus(401);
    }

    protected function getSettings($signIn = true)
    {
        $url = 'api/arrangeables/settings';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->settings[0]->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
