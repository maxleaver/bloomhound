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
        ], 2);
    }

    /** @test */
    public function a_user_can_update_arrangeable_type_settings()
    {
        $otherMarkup = create('App\Markup', ['allow_entry' => false])->id;
        $request = [
            $this->createSetting($this->settings[0]->type->id, $otherMarkup),
            $this->createSetting($this->settings[1]->type->id, $otherMarkup),
        ];

        $this->assertEquals($this->markup->id, $this->settings[0]->markup->id);
        $this->assertEquals($this->markup->id, $this->settings[1]->markup->id);

        $this->updateSettings($request)
            ->assertStatus(200);

        $this->assertEquals($otherMarkup, $this->settings[0]->fresh()->markup->id);
        $this->assertEquals($otherMarkup, $this->settings[1]->fresh()->markup->id);
    }

    /** @test */
    public function a_user_can_provide_a_value_for_markups_that_support_an_amount()
    {
        $newMarkup = create('App\Markup', ['allow_entry' => true])->id;
        $request = [
            $this->createSetting($this->settings[0]->type->id, $newMarkup, 10),
        ];

        $this->assertNotEquals($this->settings[0]->markup_value, 10);

        $this->updateSettings($request)
            ->assertStatus(200);

        $this->assertEquals($this->settings[0]->fresh()->markup_value, 10);
    }

    /** @test */
    public function a_value_is_required_if_the_markup_allows_value_entry()
    {
        $newMarkup = create('App\Markup', ['allow_entry' => true])->id;
        $request = [
            $this->createSetting($this->settings[0]->type->id, $newMarkup, null),
        ];

        $this->updateSettings($request)
            ->assertSessionHasErrors('0.markup_value');
    }

    /** @test */
    public function a_markup_value_must_be_greater_than_zero()
    {
        $newMarkup = create('App\Markup', ['allow_entry' => true])->id;
        $request = [
            $this->createSetting($this->settings[0]->type->id, $newMarkup, 0),
        ];

        $this->updateSettings($request)
            ->assertSessionHasErrors('0.markup_value');
    }

    /** @test */
    public function a_user_can_only_update_settings_that_exist()
    {
        $badSettingId = 123;
        $request = [
            $this->createSetting($badSettingId, create('App\Markup')->id),
        ];

        $this->updateSettings($request)
            ->assertSessionHasErrors('0.arrangeable_type_id');
    }

    /** @test */
    public function a_user_can_only_update_settings_with_a_markup_that_exists()
    {
        $badMarkupId = 123;
        $request = [
            $this->createSetting($this->settings[0]->type->id, $badMarkupId),
        ];

        $this->updateSettings($request)
            ->assertSessionHasErrors('0.markup_id');
    }

    /** @test */
    public function unauthenticated_users_cannot_update_a_setting()
    {
        $otherMarkup = create('App\Markup')->id;
        $request = [
            $this->createSetting($this->settings[0]->type->id, create('App\Markup')->id),
        ];

        $this->updateSettings($request, false, true)
            ->assertStatus(401);
    }

    protected function createSetting($typeId, $markupId, $value = null)
    {
        $data = [
            'arrangeable_type_id' => $typeId,
            'markup_id' => $markupId,
            'markup_value' => $value,
        ];

        return make('App\ArrangeableTypeSetting', $data)->toArray();
    }

    protected function updateSettings($request, $signIn = true, $withJson = false)
    {
        $url = 'api/arrangeables/settings';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->account->id,
            ]));
        }

        if ($withJson) {
            return $this->patchJson($url, $request);
        }

        return $this->patch($url, $request);
    }
}
