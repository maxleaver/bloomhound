<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostEventArrangementsTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->event = create('App\Event');
        $this->request = [
            'name' => 'Some arrangement name',
            'description' => 'this is a description',
            'quantity' => 10
        ];
    }

    /** @test */
    public function users_can_create_flower_arrangements_for_an_event()
    {
    	$this->assertEquals($this->event->arrangements()->count(), 0);

        $this->addArrangement($this->event->id, $this->request)
            ->assertStatus(200);

        $arrangement = $this->event->arrangements()->first();

        $this->assertEquals($this->event->arrangements()->count(), 1);
        $this->assertEquals($arrangement->name, $this->request['name']);
        $this->assertEquals($arrangement->description, $this->request['description']);
        $this->assertEquals($arrangement->quantity, $this->request['quantity']);
    }

    /** @test */
    public function an_arrangement_requires_a_name_and_a_quantity()
    {
        $this->addArrangement($this->event->id, ['quantity' => 1])
            ->assertSessionHasErrors('name');

        $this->addArrangement($this->event->id, ['name' => 'test'])
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function an_arrangement_requires_a_quantity_greater_than_zero()
    {
        $request = ['name' => 'test', 'quantity' => 0];
        $this->addArrangement($this->event->id, $request)
            ->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function users_can_only_create_arrangements_for_valid_events()
    {
        $badEventId = 123;
        $this->addArrangement($badEventId, $this->request)
            ->assertStatus(404);
    }

    /** @test */
    public function users_can_only_create_arrangements_for_events_in_their_account()
    {
        $eventInAnotherAccount = create('App\Event')->id;
        $this->addArrangement($eventInAnotherAccount, $this->request)
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_create_arrangements()
    {
        $this->postJson($this->url($this->event->id), $this->request)
            ->assertStatus(401);
    }

    protected function url($id)
    {
        return 'api/events/' . $id . '/arrangements';
    }

    protected function addArrangement($id, $request, $signIn = true)
    {
        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->event->account->id,
            ]));
        }

        return $this->post($this->url($id), $request);
    }
}
