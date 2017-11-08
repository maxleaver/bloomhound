<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArrangementTest extends TestCase
{
	use RefreshDatabase;

    protected $arrangement;

    protected function setUp()
    {
        parent::setUp();

        $this->arrangement = create('App\Arrangement');
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

        $this->updateArrangement($this->arrangement->id, $request)
            ->assertStatus(200);

        $this->assertEquals($this->arrangement->fresh()->name, $name);
        $this->assertEquals($this->arrangement->fresh()->description, $description);
        $this->assertEquals($this->arrangement->fresh()->quantity, $quantity);
    }

    /** @test */
    public function an_arrangement_requires_a_name_and_a_quantity()
    {
        $this->updateArrangement($this->arrangement->id, ['quantity' => 1])
            ->assertSessionHasErrors('name');

        $this->updateArrangement($this->arrangement->id, ['name' => 'test'])
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function an_arrangement_quantity_must_be_greater_than_zero()
    {
        $request = [
            'name' => 'new name',
            'quantity' => 0,
        ];

        $this->updateArrangement($this->arrangement->id, $request)
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function users_can_only_update_arrangements_in_their_account()
    {
        $someOtherArrangement = create('App\Arrangement')->id;
        $request = ['name' => 'some name', 'quantity' => 1];

        $this->updateArrangement($someOtherArrangement, $request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_only_update_arrangements_that_exist()
    {
        $someBadId = 666;
        $request = ['name' => 'some name', 'quantity' => 1];

        $this->updateArrangement($someBadId, $request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_arrangements()
    {
        $request = ['name' => 'some name', 'quantity' => 1];
        $this->patchJson($this->url($this->arrangement->id), $request)
            ->assertStatus(401);
    }

    protected function url($id)
    {
        return 'api/arrangements/' . $id;
    }

    protected function updateArrangement($id, $request, $signIn = true)
    {
        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->arrangement->account->id
            ]));
        }

        return $this->patch($this->url($id), $request);
    }
}
