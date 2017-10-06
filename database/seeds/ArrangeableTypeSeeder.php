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
	    	],
	    	[
	    		'name' => 'consummable',
	            'title' => 'Consummable',
	            'model' => 'item',
	    	],
	    	[
	    		'name' => 'hardgood',
	            'title' => 'Hardgood',
	            'model' => 'item',
	    	],
	    	[
	    		'name' => 'rental',
	            'title' => 'Rental',
	            'model' => 'item',
	    	],
	    ];

	    DB::table('arrangeable_types')->insert($data);
    }
}
