<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arrangement extends Model
{
	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function events()
    {
    	return $this->belongsToMany('App\Event')
    		->withPivot('quantity')
            ->withTimestamps();
    }
}
