<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetProposalArrangementsTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangements;
    protected $proposal;

    protected function setUp()
    {
        parent::setUp();

        $this->proposal = create('App\Proposal');
        $this->arrangements = create('App\Arrangement', [
            'account_id' => $this->proposal->event->account->id,
            'proposal_id' => $this->proposal->id,
        ], 10);
    }

    /** @test */
    public function users_can_get_a_list_of_arrangements_for_a_proposal()
    {
        $someOtherArrangement = create('App\Arrangement', [
            'account_id' => $this->proposal->event->account->id
        ]);

        $this->getArrangements($this->proposal->id)
            ->assertStatus(200)
            ->assertJsonFragment([$this->arrangements[0]->name])
            ->assertJsonFragment([$this->arrangements[1]->name])
            ->assertJsonMissing([$someOtherArrangement->name]);
    }

    /** @test */
    public function users_can_only_get_arrangements_for_a_proposal_in_their_account()
    {
        $someOtherProposal = create('App\Proposal')->id;
        $this->getArrangements($someOtherProposal)
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_only_get_arrangements_for_a_proposal_that_exists()
    {
        $badId = 123;
        $this->getArrangements($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_arrangements()
    {
        $this->getArrangements($this->proposal->id, false)
            ->assertStatus(401);
    }

    protected function getArrangements($id, $signIn = true)
    {
        $url = 'api/proposals/' . $id . '/arrangements';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->proposal->event->account->id,
            ]));
        }

        return $this->getJson($url);
    }
}
