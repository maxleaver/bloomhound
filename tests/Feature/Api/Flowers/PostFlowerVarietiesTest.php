<?php

namespace Tests\Feature\Api;

use App\ArrangeableTypeSetting;
use App\FlowerVariety;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostFlowerVarietiesTest extends TestCase
{
    use RefreshDatabase;

    protected $flower;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->flower = create('App\Flower');
        $this->request = ['name' => 'Some Variety'];
    }

    /** @test */
    public function users_can_add_varieties_to_a_flower()
    {
        $this->assertEquals(FlowerVariety::count(), 1);

        $this->createVariety($this->flower->id, $this->request)
            ->assertStatus(200);

        $this->assertEquals(FlowerVariety::count(), 2);
    }

    /** @test */
    public function when_a_user_creates_a_variety_the_default_markup_is_set()
    {
        $defaultSetting = ArrangeableTypeSetting::whereAccountId($this->flower->account->id)
            ->whereHas('type', function ($query) {
                return $query->whereName('flower');
            })
            ->first();
        $defaultSetting->markup_value = 10;
        $defaultSetting->save();

        $this->createVariety($this->flower->id, $this->request)
            ->assertStatus(200);

        $variety = FlowerVariety::whereName($this->request['name'])->first();

        $this->assertInstanceOf('App\Markup', $variety->markup);
        $this->assertEquals($variety->markup->id, $defaultSetting->markup->id);
        $this->assertEquals($variety->markup_value, $defaultSetting->markup_value);
    }

    /** @test */
    public function users_can_only_add_varieties_to_flowers_in_their_account()
    {
        $someOtherFlower = create('App\Flower')->id;
        $this->createVariety($someOtherFlower, $this->request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_varieties()
    {
        $this->createVariety($this->flower->id, $this->request, false)
            ->assertStatus(401);
    }

    protected function createVariety($id, $request, $signIn = true)
    {
        $url = 'api/flowers/' . $id . '/varieties';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->flower->account->id
            ]));
        }

        return $this->postJson($url, $request);
    }
}
