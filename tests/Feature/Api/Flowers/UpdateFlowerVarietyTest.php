<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateFlowerVarietyTest extends TestCase
{
    use RefreshDatabase;

    protected $variety;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->variety = create('App\FlowerVariety');
        $this->request = make('App\FlowerVariety')->toArray();
    }

    /** @test */
    public function a_user_can_update_a_flower_variety()
    {
        $this->updateVariety($this->variety->id)
            ->assertStatus(200);

        $variety = $this->variety->fresh();
        $this->assertEquals($this->request['name'], $variety->name);
        $this->assertEquals($this->request['markup_id'], $variety->markup->id);
        $this->assertEquals($this->request['markup_value'], $variety->markup_value);
        $this->assertEquals($this->request['use_default_markup'], $variety->use_default_markup);
    }

    /** @test */
    public function a_user_cannot_update_a_flower_variety_with_an_invalid_markup_id()
    {
        $badId = 123;
        $this->request['markup_id'] = $badId;

        $this->updateVariety($this->variety->id)
            ->assertSessionHasErrors('markup_id');
    }

    /** @test */
    public function a_user_can_only_update_a_flower_variety_in_their_account()
    {
        $varietyInAnotherAccount = create('App\FlowerVariety')->id;

        $this->updateVariety($varietyInAnotherAccount)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_update_flower_varieties_that_exist()
    {
        $invalidVarietyId = 123;
        $this->updateVariety($invalidVarietyId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_flower_varieties()
    {
        $this->updateVariety($this->variety->id, false, true)
            ->assertStatus(401);
    }

    protected function updateVariety($id, $signIn = true, $withJson = false)
    {
        $url = '/api/varieties/' . $id;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->variety->account->id,
            ]));
        }

        if ($withJson) {
            return $this->patchJson($url, $this->request);
        }

        return $this->patch($url, $this->request);
    }
}
