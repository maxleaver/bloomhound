<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArrangementTest extends TestCase
{
	use RefreshDatabase;

    protected $arrangement;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->arrangement = create('App\Arrangement');
        $this->request = [
            'name' => 'new name',
            'description' => 'new description',
            'quantity' => 55,
        ];
    }

    /** @test */
    public function users_can_update_an_arrangement()
    {
        $this->update($this->arrangement->id, $this->request)
            ->assertStatus(200);

        $arrangement = $this->arrangement->fresh();
        $this->assertEquals($arrangement->name, $this->request['name']);
        $this->assertEquals($arrangement->description, $this->request['description']);
        $this->assertEquals($arrangement->quantity, $this->request['quantity']);
    }

    /** @test */
    public function an_arrangement_requires_a_name()
    {
        $this->update($this->arrangement->id, ['quantity' => 1])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function an_arrangement_requires_a_quantity()
    {
        $this->update($this->arrangement->id, ['name' => 'test'])
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function an_arrangement_quantity_must_be_greater_than_zero()
    {
        $this->request['quantity'] = 0;
        $this->update($this->arrangement->id, $this->request)
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function a_price_is_required_if_override_is_true()
    {
        $this->request['override_price'] = true;
        $this->update($this->arrangement->id, $this->request)
            ->assertSessionHasErrors('price');
    }

    /** @test */
    public function a_user_can_override_the_price_of_an_arrangement()
    {
        $this->request['override_price'] = true;
        $this->request['price'] = 500;
        $this->update($this->arrangement->id, $this->request);

        $arrangement = $this->arrangement->fresh();
        $this->assertTrue($arrangement->override_price);
        $this->assertEquals(500, $arrangement->price);
    }

    /** @test */
    public function an_arrangement_price_cannot_be_zero_or_less()
    {
        $this->request['override_price'] = true;
        $this->request['price'] = 0;
        $this->update($this->arrangement->id, $this->request)
            ->assertSessionHasErrors('price');
    }

    /** @test */
    public function users_cannot_update_arrangements_in_other_accounts()
    {
        $someOtherArrangement = create('App\Arrangement')->id;
        $this->update($someOtherArrangement, $this->request)
            ->assertStatus(403);
    }

    /** @test */
    public function users_cannot_update_invalid_arrangements()
    {
        $someBadId = 666;
        $this->update($someBadId, $this->request)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_arrangements()
    {
        $this->update($this->arrangement->id, $this->request, false, true)
            ->assertStatus(401);
    }

    protected function update($id, $request, $signIn = true, $withJson = false)
    {
        $url = 'api/arrangements/' . $id;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->arrangement->account->id
            ]));
        }

        if ($withJson) {
            return $this->patchJson($url, $request);
        }

        return $this->patch($url, $request);
    }
}
