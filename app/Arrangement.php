<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arrangement extends Model
{
	protected $guarded = [];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function event()
    {
    	return $this->belongsTo('App\Event');
    }

    public function ingredients()
    {
        return $this->hasMany('App\ArrangementIngredient');
    }
}
