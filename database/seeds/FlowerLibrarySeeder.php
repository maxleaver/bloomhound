<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class FlowerLibrarySeeder extends Seeder
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
	    		'type' => 'default',
	    		'name' => 'Default',
	            'description' => 'Default library of flowers',
	    	],
	    	[
	    		'type' => 'custom',
	    		'name' => 'Custom',
	            'description' => 'User customized library of flowers',
	    	],
	    	[
	    		'type' => 'vendor',
	    		'name' => 'Vendor',
	            'description' => 'Third-party library of flowers',
	    	],
	    ];

	    DB::table('flower_libraries')->insert($data);
    }
}
