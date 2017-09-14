<?php

namespace Tests\Feature\Api;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostEventsTest extends TestCase
{
    use RefreshDatabase;

    protected $request;
    protected $user;
    protected $url;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->request = [
            'name' => 'Event Name',
            'date' => '2017-09-12T12:37:55.729Z'
        ];
        $this->url = 'api/events';
    }

    /** @test */
    public function an_authenticated_user_can_add_an_event()
    {
    	$this->assertEquals(Event::count(), 0);

    	$this->signIn($this->user)
            ->postJson($this->url, $this->request)
    		->assertStatus(200);

    	$this->assertEquals(Event::count(), 1);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_events()
    {
    	$this->postJson($this->url, $this->request)
    		->assertStatus(401);
    }
}
