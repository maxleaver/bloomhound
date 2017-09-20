<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlowerLibrary extends Model
{
	public function flowers()
    {
    	return $this->hasMany('App\Flower');
    }
}
