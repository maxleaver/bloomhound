<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountSettingTest extends TestCase
{
    use RefreshDatabase;

    protected $setting;

    protected function setUp()
    {
        parent::setUp();

        Event::fake();

        $this->setting = create('App\AccountSetting');
    }

    /** @test */
    public function an_account_setting_has_a_use_tax_property()
    {
        $this->assertNotNull($this->setting->use_tax);
    }

    /** @test */
    public function an_account_setting_can_have_a_tax_amount()
    {
        $this->setting->tax_amount = 10;
        $this->setting->save();

        $this->assertNotNull($this->setting->tax_amount);
        $this->assertEquals($this->setting->tax_amount, 10);
    }

    /** @test */
    public function account_settings_belong_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->setting->account);
    }
}
