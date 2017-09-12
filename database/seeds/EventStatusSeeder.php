<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class EventStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $data = [
	    	[
	    		'name' => 'draft',
	            'title' => 'Draft',
	    	],
	    	[
	    		'name' => 'sent',
	            'title' => 'Proposal Sent',
	    	],
	    	[
	    		'name' => 'accepted',
	            'title' => 'Proposal Accepted',
	    	],
	    	[
	    		'name' => 'completed',
	            'title' => 'Event Completed',
	    	],
	    ];

	    DB::table('event_statuses')->insert($data);
    }
}
