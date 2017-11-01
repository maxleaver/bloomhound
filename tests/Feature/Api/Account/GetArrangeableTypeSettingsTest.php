<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetArrangeableTypeSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $settings;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->url = 'api/arrangeables/settings';
        $this->user = create('App\User');
        $this->settings = create('App\ArrangeableTypeSetting', [
            'account_id' => $this->user->account->id
        ], 3);
    }

    /** @test */
    public function users_can_get_arrangeable_type_settings()
    {
        $otherSetting = create('App\ArrangeableTypeSetting');

        $this->signIn($this->user)
            ->getJson($this->url)
            ->assertStatus(200)
            ->assertJsonFragment([$this->settings[0]->type->name])
            ->assertJsonFragment([$this->settings[1]->type->name])
            ->assertJsonMissing([$otherSetting->type->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_arrangeable_type_settings()
    {
        $this->getJson($this->url)
            ->assertStatus(401);
    }
}
