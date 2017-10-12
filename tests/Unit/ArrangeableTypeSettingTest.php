<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArrangeableTypeSettingTest extends TestCase
{
    use RefreshDatabase;

    protected $setting;

    protected function setUp()
    {
    	parent::setUp();

        $this->setting = create('App\ArrangeableTypeSetting');
    }

    /** @test */
    public function an_arrangeable_type_setting_has_a_markup_value()
    {
        $this->assertNotNull($this->setting->markup_value);
    }

    /** @test */
    public function an_arrangeable_type_setting_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->setting->account);
    }

    /** @test */
    public function an_arrangeable_type_setting_belongs_to_an_arrangeable_type()
    {
        $this->assertInstanceOf('App\ArrangeableType', $this->setting->type);
    }

    /** @test */
    public function an_arrangeable_type_setting_belongs_to_a_markup()
    {
        $this->assertInstanceOf('App\Markup', $this->setting->markup);
    }
}
