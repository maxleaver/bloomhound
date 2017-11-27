<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetArrangeablesTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $items;
    protected $varieties;

    protected function setUp()
    {
        parent::setUp();

        $this->account = create('App\Account');
        $this->items = create('App\Item', [
            'account_id' => $this->account->id
        ], 2);
        $this->varieties = create('App\FlowerVariety', [
            'account_id' => $this->account->id
        ], 2);
    }

    /** @test */
    public function a_user_can_get_a_list_of_arrangeable_items()
    {
        $itemInAnotherAccount = create('App\Item');

        $this->getArrangeables()
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
        $this->getArrangeables(false)
            ->assertStatus(401);
    }

    protected function getArrangeables($signIn = true)
    {
        $url = 'api/arrangeables';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
