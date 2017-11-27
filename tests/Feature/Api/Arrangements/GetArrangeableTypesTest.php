<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetArrangeableTypesTest extends TestCase
{
    use RefreshDatabase;

    protected $types;

    protected function setUp()
    {
        parent::setUp();

        $this->types = create('App\ArrangeableType', ['model' => 'item'], 3);
    }

    /** @test */
    public function a_user_can_get_arrangeable_types()
    {
        $this->getTypes()
            ->assertStatus(200)
            ->assertJsonFragment([$this->types[0]->title])
            ->assertJsonFragment([$this->types[1]->title]);
    }

    /** @test */
    public function a_user_can_get_arrangeable_types_for_a_specific_model()
    {
        $anotherModel = create('App\ArrangeableType', ['model' => 'flowervariety']);

        $this->getTypes('item')
            ->assertStatus(200)
            ->assertJsonFragment([$this->types[0]->title])
            ->assertJsonFragment([$this->types[1]->title])
            ->assertJsonMissing([$anotherModel->title]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_arrangeable_types()
    {
        $this->getTypes('item', false)
            ->assertStatus(401);
    }

    protected function getTypes($type = null, $signIn = true)
    {
        $url = $type ? 'api/arrangeables/types?type=' . $type : 'api/arrangeables/types';

        if ($signIn) {
            $this->signIn(create('App\User'));
        }

        return $this->getJson($url);
    }
}
