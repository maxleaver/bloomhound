<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArrangementIngredient extends Model
{
	protected $guarded = [];
	protected $with = ['arrangeable'];

	public function arrangement()
    {
        return $this->belongsTo('App\Arrangement');
    }

    public function arrangeable()
    {
    	return $this->morphTo();
    }
}
