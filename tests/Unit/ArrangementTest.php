<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArrangementTest extends TestCase
{
    use RefreshDatabase;

    protected $arrangement;

    protected function setUp()
    {
    	parent::setUp();

        $this->arrangement = create('App\Arrangement');
    }

    /** @test */
    public function an_arrangement_has_a_name() {
        $this->assertNotNull($this->arrangement->name);
    }

    /** @test */
    public function an_arrangement_belongs_to_an_account()
    {
        $this->assertInstanceOf('App\Account', $this->arrangement->account);
    }

    /** @test */
    public function an_arrangement_can_have_many_events()
    {
        create('App\Event', ['account_id' => $this->arrangement->account->id], 5)
            ->each(function($event) {
                $event->arrangements()->save($this->arrangement, ['quantity' => 10]);
            });

        $this->assertInstanceOf('App\Event', $this->arrangement->events->first());
    }
}
