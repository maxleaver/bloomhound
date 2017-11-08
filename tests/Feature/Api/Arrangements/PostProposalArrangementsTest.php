<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostProposalArrangementsTest extends TestCase
{
    use RefreshDatabase;

    protected $proposal;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
        $this->request = [
            'name' => 'Some arrangement name',
            'description' => 'this is a description',
            'quantity' => 10
        ];
    }

    /** @test */
    public function users_can_create_flower_arrangements_for_a_proposal()
    {
    	$this->assertEquals($this->proposal->arrangements()->count(), 0);

        $this->addArrangement($this->proposal->id, $this->request)
            ->assertStatus(200);

        $arrangement = $this->proposal->arrangements()->first();

        $this->assertEquals($this->proposal->arrangements()->count(), 1);
        $this->assertEquals($arrangement->name, $this->request['name']);
        $this->assertEquals($arrangement->description, $this->request['description']);
        $this->assertEquals($arrangement->quantity, $this->request['quantity']);
    }

    /** @test */
    public function an_arrangement_requires_a_name_and_a_quantity()
    {
        $this->addArrangement($this->proposal->id, ['quantity' => 1])
            ->assertSessionHasErrors('name');

        $this->addArrangement($this->proposal->id, ['name' => 'test'])
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function an_arrangement_requires_a_quantity_greater_than_zero()
    {
        $request = ['name' => 'test', 'quantity' => 0];
        $this->addArrangement($this->proposal->id, $request)
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function arrangements_cannot_be_created_for_invalid_proposals()
    {
        $badId = 123;
        $this->addArrangement($badId, $this->request)
            ->assertStatus(404);
    }

    /** @test */
    public function arrangements_cannot_be_added_to_proposals_in_other_accounts()
    {
        $proposalInAnotherAccount = create('App\Proposal')->id;
        $this->addArrangement($proposalInAnotherAccount, $this->request)
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_create_arrangements()
    {
        $this->postJson($this->url($this->proposal->id), $this->request)
            ->assertStatus(401);
    }

    protected function url($id)
    {
        return 'api/proposals/' . $id . '/arrangements';
    }

    protected function addArrangement($id, $request, $signIn = true)
    {
        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        return $this->post($this->url($id), $request);
    }
}
