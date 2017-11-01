<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetArrangeableTypesTest extends TestCase
{
    use RefreshDatabase;

    protected $types;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->types = create('App\ArrangeableType', ['model' => 'item'], 3);
        $this->user = create('App\User');
        $this->url = 'api/arrangeables/types';
    }

    /** @test */
    public function a_user_can_get_arrangeable_types()
    {
        $this->signIn($this->user)
            ->getJson($this->url)
            ->assertStatus(200)
            ->assertJsonFragment([$this->types[0]->title])
    		->assertJsonFragment([$this->types[1]->title]);
    }

    /** @test */
    public function a_user_can_get_arrangeable_types_for_a_specific_model()
    {
        $anotherModel = create('App\ArrangeableType', ['model' => 'flowervariety']);

        $this->signIn($this->user)
            ->getJson($this->url . '?type=item')
            ->assertStatus(200)
            ->assertJsonFragment([$this->types[0]->title])
            ->assertJsonFragment([$this->types[1]->title])
            ->assertJsonMissing([$anotherModel->title]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_arrangeable_types()
    {
        $this->getJson($this->url)
            ->assertStatus(401);
    }
}
