<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostProposalsTest extends TestCase
{
    use RefreshDatabase;

    protected $event;

    protected function setUp()
    {
        parent::setUp();

        $this->event = create('App\Event');
    }

    /** @test */
    public function a_user_can_create_a_proposal_for_an_event()
    {
        $this->assertEquals(1, $this->event->proposals()->count());

        $this->createProposal($this->event->id)
            ->assertStatus(200);

        $this->assertEquals(2, $this->event->proposals()->count());
    }

    /** @test */
    public function the_version_number_increments_from_the_newest_proposal()
    {
        $this->assertEquals(1, $this->event->proposals()->count());
        $this->assertEquals(1, $this->event->proposals()->first()->version);

        $this->createProposal($this->event->id)
            ->assertStatus(200);

        $this->assertEquals(2, $this->event->proposals()->count());
        $this->assertEquals(2, $this->event->proposals()->skip(1)->first()->version);

        $this->createProposal($this->event->id)
            ->assertStatus(200);

        $this->assertEquals(3, $this->event->proposals()->count());
        $this->assertEquals(3, $this->event->proposals()->skip(2)->first()->version);
    }

    /** @test */
    public function a_new_proposal_is_the_active_proposal()
    {
        $firstProposal = $this->event->proposals->first();
        $this->assertNotNull($firstProposal);
        $this->assertEquals(1, $this->event->proposals->count());
        $this->assertEquals($this->event->active_proposal_id, $firstProposal->id);

        $this->createProposal($this->event->id)
            ->assertStatus(200);

        $newProposal = $this->event->proposals()->skip(1)->first();
        $this->assertNotNull($newProposal);
        $this->assertEquals($this->event->fresh()->active_proposal_id, $newProposal->id);
    }

    /** @test */
    public function a_new_proposal_clones_the_children_of_the_active_proposal()
    {
        $activeProposal = $this->event->active_proposal;
        create('App\Arrangement', [
            'proposal_id' => $activeProposal->id,
        ], 5);
        create('App\Delivery', [
            'proposal_id' => $activeProposal->id,
        ], 5);
        create('App\Setup', [
            'proposal_id' => $activeProposal->id,
        ], 5);

        $vendors = create('App\Vendor', [], 5);
        $activeProposal->vendors()->attach($vendors);

        $this->assertEquals(1, $activeProposal->id);
        $this->assertEquals(5, $activeProposal->arrangements->count());
        $this->assertEquals(5, $activeProposal->deliveries->count());
        $this->assertEquals(5, $activeProposal->setups->count());
        $this->assertEquals(5, $activeProposal->vendors->count());

        $this->createProposal($this->event->id);

        $activeProposal = $this->event->fresh()->active_proposal;
        $this->assertEquals(2, $activeProposal->id);
        $this->assertEquals(5, $activeProposal->arrangements->count());
        $this->assertEquals(5, $activeProposal->deliveries->count());
        $this->assertEquals(5, $activeProposal->setups->count());
        $this->assertEquals(5, $activeProposal->vendors->count());
    }

    /** @test */
    public function a_user_can_only_create_proposals_for_events_in_their_account()
    {
        $someOtherEvent = create('App\Event')->id;
        $this->createProposal($someOtherEvent)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_only_create_proposals_for_existing_events()
    {
        $badId = 666;
        $this->createProposal($badId)
            ->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_cannot_create_proposals()
    {
        $this->createProposal($this->event->id, false)
            ->assertStatus(401);
    }

    protected function createProposal($id, $signIn = true)
    {
        $url = 'api/events/' . $id . '/proposals';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->event->account->id
            ]));
        }

        return $this->postJson($url);
    }
}
