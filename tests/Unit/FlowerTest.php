<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlowerTest extends TestCase
{
    use RefreshDatabase;

    protected $flower;

	protected function setUp()
    {
    	parent::setUp();

        $this->flower = create('App\Flower');
    }

    /** @test */
    public function a_flower_has_a_name()
    {
        $this->assertNotNull($this->flower->name);
    }

    /** @test */
    public function a_flower_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->flower->account);
    }

    /** @test */
    public function a_flower_creates_a_default_variety_on_creation()
    {
        $this->assertInstanceOf('App\FlowerVariety', $this->flower->varieties->first());
        $this->assertEquals($this->flower->varieties->first()->name, 'Default');
    }

    /** @test */
    public function a_flower_has_notes()
    {
        create('App\Note', [
            'user_id' => create('App\User', ['account_id' => $this->flower->account->id]),
            'notable_id' => $this->flower->id,
            'notable_type' => 'App\Flower',
            'text' => 'This is a note.',
        ]);

        $this->assertInstanceOf('App\Note', $this->flower->notes->first());
    }
}
