<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEventArrangementTest extends TestCase
{
	use RefreshDatabase;

    protected $arrangement;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->arrangement = create('App\Arrangement');
        $this->user = create('App\User', [
            'account_id' => $this->arrangement->account->id
        ]);
    }

    protected function url($id)
    {
        return 'api/arrangements/' . $id;
    }

    /** @test */
    public function users_can_update_an_arrangement()
    {
        $name = 'new name';
        $description = 'new description';
        $quantity = 55;
        $request = [
            'name' => $name,
            'description' => $description,
            'quantity' => $quantity
        ];

        $this->signIn($this->user)
            ->patchJson($this->url($this->arrangement->id), $request)
            ->assertStatus(200);

        $this->assertEquals($this->arrangement->fresh()->name, $name);
        $this->assertEquals($this->arrangement->fresh()->description, $description);
        $this->assertEquals($this->arrangement->fresh()->quantity, $quantity);
    }

    /** @test */
    public function users_can_only_update_arrangements_in_their_account()
    {
        $someOtherArrangement = create('App\Arrangement');
        $request = ['name' => 'some name'];

        $this->signIn($this->user)
            ->patchJson($this->url($someOtherArrangement->id), $request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_update_arrangements_that_exist()
    {
        $someBadId = 666;
        $request = ['name' => 'some name'];

        $this->signIn($this->user)
            ->patchJson($this->url($someBadId), $request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_arrangements()
    {
        $request = ['name' => 'some name'];
        $this->patchJson($this->url($this->arrangement->id), $request)
            ->assertStatus(401);
    }
}