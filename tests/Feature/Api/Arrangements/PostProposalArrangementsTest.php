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

        $this->addArrangement($this->proposal->id)
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
        $this->request['name'] = null;
        $this->addArrangement($this->proposal->id)
            ->assertSessionHasErrors('name');

        $this->request['name'] = 'test';
        $this->request['quantity'] = null;
        $this->addArrangement($this->proposal->id)
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function an_arrangement_requires_a_quantity_greater_than_zero()
    {
        $this->request['quantity'] = 0;
        $this->addArrangement($this->proposal->id)
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function arrangements_cannot_be_created_for_invalid_proposals()
    {
        $badId = 123;
        $this->addArrangement($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function arrangements_cannot_be_added_to_proposals_in_other_accounts()
    {
        $proposalInAnotherAccount = create('App\Proposal')->id;
        $this->addArrangement($proposalInAnotherAccount)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_create_arrangements()
    {
        $this->addArrangement($this->proposal->id, false, true)
            ->assertStatus(401);
    }

    protected function addArrangement($id, $signIn = true, $withJson = false)
    {
        $url = 'api/proposals/' . $id . '/arrangements';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        if ($withJson) {
            return $this->postJson($url, $this->request);
        }

        return $this->post($url, $this->request);
    }
}
