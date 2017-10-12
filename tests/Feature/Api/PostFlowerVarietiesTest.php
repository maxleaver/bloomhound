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
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->flower = create('App\Flower', ['account_id' => $this->user->account->id]);
        $this->request = ['name' => 'Some Variety'];
    }

    protected function url($id)
    {
        return 'api/flowers/' . $id . '/varieties';
    }

    /** @test */
    public function users_can_add_varieties_to_a_flower()
    {
        $this->assertEquals(FlowerVariety::count(), 1);

        $this->signIn($this->user)
            ->postJson($this->url($this->flower->id), $this->request)
            ->assertStatus(200);

        $this->assertEquals(FlowerVariety::count(), 2);
    }

    /** @test */
    public function when_a_user_creates_a_variety_the_default_markup_is_set()
    {
        $defaultSetting = ArrangeableTypeSetting::whereAccountId($this->flower->account->id)
            ->whereHas('type', function($query) {
                return $query->whereName('flower');
            })
            ->first();
        $defaultSetting->markup_value = 10;
        $defaultSetting->save();

        $this->signIn($this->user)
            ->postJson($this->url($this->flower->id), $this->request)
            ->assertStatus(200);

        $variety = FlowerVariety::whereName($this->request['name'])->first();

        $this->assertInstanceOf('App\Markup', $variety->markup);
        $this->assertEquals($variety->markup->id, $defaultSetting->markup->id);
        $this->assertEquals($variety->markup_value, $defaultSetting->markup_value);
    }

    /** @test */
    public function users_can_only_add_varieties_to_flowers_in_their_account()
    {
        $someOtherFlower = create('App\Flower');

        $this->signIn($this->user)
            ->postJson($this->url($someOtherFlower->id), $this->request)
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_varieties()
    {
        $this->postJson($this->url($this->flower->id), $this->request)
            ->assertStatus(401);
    }
}
