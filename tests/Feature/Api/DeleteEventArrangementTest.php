<?php

namespace Tests\Feature\Api;

use App\Arrangement;
use App\ArrangementIngredient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteEventArrangementTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangement;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->arrangement = create('App\Arrangement', ['account_id' => $this->user->account->id]);
    }

    /** @test */
    public function a_user_can_delete_an_arrangement()
    {
        $this->assertEquals(Arrangement::count(), 1);

        $this->signIn($this->user)
            ->deleteJson($this->url($this->arrangement->id))
            ->assertStatus(200);

        $this->assertEquals(Arrangement::count(), 0);
    }

    /** @test */
    public function deleting_an_arrangement_also_deletes_the_arrangements_ingredients()
    {
        $ingredients = create('App\ArrangementIngredient', [
            'arrangement_id' => $this->arrangement->id
        ], 10);

        $this->assertEquals(
            ArrangementIngredient::where('arrangement_id', $this->arrangement->id)->count(),
            10
        );

        $this->signIn($this->user)
            ->deleteJson($this->url($this->arrangement->id))
            ->assertStatus(200);

        $this->assertEquals(
            ArrangementIngredient::where('arrangement_id', $this->arrangement->id)->count(),
            0
        );
    }

    /** @test */
    public function users_cannot_delete_arrangements_that_dont_exist()
    {
        $someWrongId = 123;

        $this->signIn($this->user)
            ->deleteJson($this->url($someWrongId))
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_delete_arrangements_in_other_accounts()
    {
        $arrangementInAnotherAccount = create('App\Arrangement');

        $this->signIn($this->user)
            ->deleteJson($this->url($arrangementInAnotherAccount->id))
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_arrangements()
    {
        $this->deleteJson($this->url($this->arrangement->id))
            ->assertStatus(401);
    }

    protected function url($id)
    {
    	return 'api/arrangements/' . $id;
    }
}