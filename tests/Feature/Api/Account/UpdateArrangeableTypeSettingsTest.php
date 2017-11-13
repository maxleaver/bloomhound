<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArrangeableTypeSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $markup;
    protected $setting;

    protected function setUp()
    {
        parent::setUp();

        $this->account = create('App\Account');
        $this->markup = create('App\Markup', ['allow_entry' => false]);
        $this->settings = create('App\ArrangeableTypeSetting', [
            'account_id' => $this->account->id,
            'markup_id' => $this->markup->id,
        ], 4);
    }

    /** @test */
    public function a_user_can_update_arrangeable_type_settings()
    {
        $otherMarkup = create('App\Markup');
        $request = [
            [
                'arrangeable_type_id' => $this->settings[0]->type->id,
                'markup_id' => $otherMarkup->id,
            ],
            [
                'arrangeable_type_id' => $this->settings[1]->type->id,
                'markup_id' => $otherMarkup->id,
            ]
        ];

        $this->assertEquals($this->markup->id, $this->settings[0]->markup->id);
        $this->assertEquals($this->markup->id, $this->settings[1]->markup->id);

        $this->updateSettings($request)
            ->assertStatus(200);

        $this->assertEquals($otherMarkup->id, $this->settings[0]->fresh()->markup->id);
        $this->assertEquals($otherMarkup->id, $this->settings[1]->fresh()->markup->id);
    }

    /** @test */
    public function a_user_can_provide_a_value_for_markups_that_support_an_amount()
    {
        $otherMarkup = create('App\Markup', ['allow_entry' => true]);
        $request = [
            [
                'arrangeable_type_id' => $this->settings[0]->type->id,
                'markup_id' => $otherMarkup->id,
                'markup_value' => 10,
            ],
        ];

        $this->assertNotEquals($this->settings[0]->markup_value, 10);

        $this->updateSettings($request)
            ->assertStatus(200);

        $this->assertEquals($this->settings[0]->fresh()->markup_value, 10);
    }

    /** @test */
    public function a_user_must_provide_a_value_if_the_markup_requires_it()
    {
        $otherMarkup = create('App\Markup', ['allow_entry' => true]);
        $request = [
            [
                'arrangeable_type_id' => $this->settings[0]->type->id,
                'markup_id' => $otherMarkup->id,
                'markup_value' => null,
            ],
        ];

        $this->updateSettings($request)
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_can_only_update_settings_that_exist()
    {
        $badSettingId = 123;
        $request = [
            [
                'arrangeable_type_id' => $badSettingId,
                'markup_id' => create('App\Markup')->id,
            ]
        ];

        $this->updateSettings($request)
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_can_only_update_settings_with_a_markup_that_exists()
    {
        $badMarkupId = 123;
        $request = [
            [
                'arrangeable_type_id' => $this->settings[0]->type->id,
                'markup_id' => $badMarkupId,
            ]
        ];

        $this->updateSettings($request)
            ->assertStatus(422);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_a_setting()
    {
        $otherMarkup = create('App\Markup')->id;
        $request = [
            [
                'arrangeable_type_id' => $this->settings[0]->type->id,
                'markup_id' => $otherMarkup,
            ]
        ];

        $this->updateSettings($request, false)
            ->assertStatus(401);
    }

    protected function updateSettings($request, $signIn = true)
    {
        $url = 'api/arrangeables/settings';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->account->id,
            ]));
        }

        return $this->patchJson($url, $request);
    }
}
