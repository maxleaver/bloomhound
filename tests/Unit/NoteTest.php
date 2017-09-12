<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoteTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function a_note_has_a_user()
    {
        $note = create('App\Note');
        $this->assertInstanceOf('App\User', $note->user);
    }

	/** @test */
    public function a_note_has_notables()
    {
        $customer = create('App\Customer');
        $note = create('App\Note', [
            'notable_id' => $customer->id,
            'notable_type' => 'App\Customer',
        ]);

        $this->assertInstanceOf('App\Customer', $note->notable->first());
    }
}
