<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ArrangeableTypeSeeder extends Seeder
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
	    		'name' => 'flower',
	            'title' => 'Flower',
	            'model' => 'flowervariety',
	            'default_markup_id' => 1,
	    	],
	    	[
	    		'name' => 'consummable',
	            'title' => 'Consummable',
	            'model' => 'item',
	            'default_markup_id' => 1,
	    	],
	    	[
	    		'name' => 'hardgood',
	            'title' => 'Hardgood',
	            'model' => 'item',
	            'default_markup_id' => 1,
	    	],
	    	[
	    		'name' => 'rental',
	            'title' => 'Rental',
	            'model' => 'item',
	            'default_markup_id' => 1,
	    	],
	    ];

	    DB::table('arrangeable_types')->insert($data);
    }
}
