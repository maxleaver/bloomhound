<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateFlowerVarietyTest extends TestCase
{
    use RefreshDatabase;

    protected $variety;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->variety = create('App\FlowerVariety', [
            'account_id' => $this->user->account->id
        ]);

        $name = 'test name';
        $markup_id = \App\Markup::whereName('cost_plus_percent')->first()->id;
        $markup_value = 50;
        $use_default_markup = false;
        $this->request = compact(
            'name',
            'markup_id',
            'markup_value',
            'use_default_markup'
        );
    }

    protected function makeRequest($varietyId, $signIn = true)
    {
        $url = '/api/varieties/' . $varietyId;

        if ($signIn) {
            return $this->signIn($this->user)->patchJson($url, $this->request);
        }

        return $this->patchJson($url, $this->request);
    }

    /** @test */
    public function a_user_can_update_a_flower_variety()
    {
        $this->makeRequest($this->variety->id)
            ->assertStatus(200);

        $variety = $this->variety->fresh();
        $this->assertEquals($this->request['name'], $variety->name);
        $this->assertEquals($this->request['markup_id'], $variety->markup->id);
        $this->assertEquals($this->request['markup_value'], $variety->markup_value);
        $this->assertFalse($variety->use_default_markup);
    }

    /** @test */
    public function a_user_cannot_update_a_flower_variety_with_an_invalid_markup_id()
    {
        $invalidMarkupId = 123;
        $this->request['markup_id'] = $invalidMarkupId;
        $this->makeRequest($this->variety->id)
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_can_only_update_a_flower_variety_in_their_account()
    {
        $varietyInAnotherAccount = create('App\FlowerVariety');
        $this->makeRequest($varietyInAnotherAccount->id)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_update_flower_varieties_that_exist()
    {
        $invalidVarietyId = 123;
        $this->makeRequest($invalidVarietyId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_flower_varieties()
    {
        $this->makeRequest($this->variety->id, false)
            ->assertStatus(401);
    }
}
