<?php

namespace Tests\Feature\Api;

use App\Arrangement;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteArrangementTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangement;

    protected function setUp()
    {
        parent::setUp();

        $this->arrangement = create('App\Arrangement');
    }

    /** @test */
    public function a_user_can_delete_an_arrangement()
    {
        $this->assertEquals(Arrangement::count(), 1);

        $this->deleteArrangement($this->arrangement->id)
            ->assertStatus(200);

        $this->assertEquals(Arrangement::count(), 0);
    }

    /** @test */
    public function deleting_an_arrangement_also_deletes_the_arrangements_ingredients()
    {
        $ingredients = create('App\ArrangementIngredient', [
            'arrangement_id' => $this->arrangement->id
        ], 10);

        $this->assertEquals(10, $this->arrangement->ingredients()->count());

        $this->deleteArrangement($this->arrangement->id)
            ->assertStatus(200);

        $this->assertEquals(0, $this->arrangement->ingredients()->count());
    }

    /** @test */
    public function users_cannot_delete_arrangements_that_dont_exist()
    {
        $badId = 123;
        $this->deleteArrangement($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function users_cannot_delete_arrangements_in_other_accounts()
    {
        $arrangementInAnotherAccount = create('App\Arrangement');
        $this->deleteArrangement($arrangementInAnotherAccount->id)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_arrangements()
    {
        $this->deleteArrangement($this->arrangement->id, false)
            ->assertStatus(401);
    }

    protected function deleteArrangement($arrangementId, $signIn = true)
    {
        $url = 'api/arrangements/' . $arrangementId;

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->arrangement->account->id,
            ]));
        }

        return $this->deleteJson($url);
    }
}
