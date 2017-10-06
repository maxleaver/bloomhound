<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ItemTypeSeeder extends Seeder
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
	    		'name' => 'consummable',
	            'title' => 'Consummable',
	    	],
	    	[
	    		'name' => 'hardgood',
	            'title' => 'Hardgood',
	    	],
	    	[
	    		'name' => 'rental',
	            'title' => 'Rental',
	    	],
	    ];

	    DB::table('item_types')->insert($data);
    }
}
