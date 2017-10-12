<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArrangeableTypeSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $markup;
    protected $setting;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->markup = create('App\Markup', ['allow_entry' => false]);
        $this->settings = create('App\ArrangeableTypeSetting', [
            'account_id' => $this->user->account->id,
            'markup_id' => $this->markup->id,
        ], 4);
        $this->url = 'api/arrangeables/settings';
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

        $this->assertEquals($this->settings[0]->markup->id, $this->markup->id);
        $this->assertEquals($this->settings[1]->markup->id, $this->markup->id);

        $this->signIn($this->user)
            ->patchJson($this->url, $request)
            ->assertStatus(200);

        $this->assertEquals($this->settings[0]->fresh()->markup->id, $otherMarkup->id);
        $this->assertEquals($this->settings[1]->fresh()->markup->id, $otherMarkup->id);
    }

    /** @test */
    public function a_user_can_provide_a_value_for_markups_that_support_an_amount()
    {
        $otherMarkup = create('App\Markup', [
            'allow_entry' => true,
        ]);
        $request = [
            [
                'arrangeable_type_id' => $this->settings[0]->type->id,
                'markup_id' => $otherMarkup->id,
                'markup_value' => 10,
            ],
        ];

        $this->assertNotEquals($this->settings[0]->markup_value, 10);

        $this->signIn($this->user)
            ->patchJson($this->url, $request)
            ->assertStatus(200);

        $this->assertEquals($this->settings[0]->fresh()->markup_value, 10);
    }

    /** @test */
    public function a_user_must_provide_a_value_if_the_markup_requires_it()
    {
        $otherMarkup = create('App\Markup', [
            'allow_entry' => true,
        ]);
        $request = [
            [
                'arrangeable_type_id' => $this->settings[0]->type->id,
                'markup_id' => $otherMarkup->id,
                'markup_value' => null,
            ],
        ];

        $this->signIn($this->user)
            ->patchJson($this->url, $request)
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_can_only_update_settings_that_exist()
    {
        $badSettingId = 123;
        $otherMarkup = create('App\Markup');
        $request = [
            [
                'arrangeable_type_id' => $badSettingId,
                'markup_id' => $otherMarkup->id,
            ]
        ];

        $this->signIn($this->user)
            ->patchJson($this->url, $request)
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

        $this->signIn($this->user)
            ->patchJson($this->url, $request)
            ->assertStatus(422);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_a_setting()
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

        $this->patchJson($this->url, $request)
            ->assertStatus(401);
    }
}
