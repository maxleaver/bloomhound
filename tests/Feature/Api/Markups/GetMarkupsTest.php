<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetMarkupsTest extends TestCase
{
    use RefreshDatabase;

    protected $markups;

    protected function setUp()
    {
        parent::setUp();

        $this->markups = create('App\Markup', [], 3);
    }

    /** @test */
    public function a_user_can_get_markups()
    {
        $this->getMarkups()
            ->assertStatus(200)
            ->assertJsonFragment([$this->markups[0]->title])
            ->assertJsonFragment([$this->markups[1]->title]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_markups()
    {
        $this->getMarkups(false)
            ->assertStatus(401);
    }

    protected function getMarkups($signIn = true)
    {
        $url = 'api/markups';

        if ($signIn) {
            $this->signIn(create('App\User'));
        }

        return $this->getJson($url);
    }
}
