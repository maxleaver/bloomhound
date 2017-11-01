<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetArrangeablesTest extends TestCase
{
    use RefreshDatabase;

    protected $items;
    protected $url;
    protected $user;
    protected $varieties;

    protected function setUp()
    {
        parent::setUp();

        $this->url = 'api/arrangeables';
        $this->user = create('App\User');
        $this->items = create(
        	'App\Item',
        	['account_id' => $this->user->account->id],
        	2
       	);
        $this->varieties = create(
        	'App\FlowerVariety',
        	['account_id' => $this->user->account->id],
        	2
       	);
    }

    /** @test */
    public function a_user_can_get_a_list_of_arrangeable_items()
    {
    	$itemInAnotherAccount = create('App\Item');

        $this->signIn($this->user)
            ->getJson($this->url)
    		->assertStatus(200)
    		->assertJsonFragment([$this->items[0]->name])
    		->assertJsonFragment([$this->items[1]->name])
    		->assertJsonFragment([$this->varieties[0]->name])
    		->assertJsonFragment([$this->varieties[1]->name])
            ->assertJsonMissing([$itemInAnotherAccount->name]);
    }

    /** @test */
    public function unauthenticated_users_cannot_get_arrangeables()
    {
    	$this->getJson($this->url)
    		->assertStatus(401);
    }
}
