<?php

namespace Tests\Api\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetMarkupsTest extends TestCase
{
    use RefreshDatabase;

    protected $markups;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->markups = create('App\Markup', [], 3);
        $this->user = create('App\User');
        $this->url = 'api/markups';
    }

    /** @test */
    public function a_user_can_get_markups()
    {
        $this->signIn($this->user)
            ->getJson($this->url)
            ->assertStatus(200)
            ->assertJsonFragment([$this->markups[0]->title])
    		->assertJsonFragment([$this->markups[1]->title]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_markups()
    {
        $this->getJson($this->url)
            ->assertStatus(401);
    }
}
