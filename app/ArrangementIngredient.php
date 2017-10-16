<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArrangementIngredient extends Model
{
	protected $guarded = [];
	protected $with = ['arrangeable'];
    protected $appends = ['cost', 'price'];

	public function arrangement()
    {
        return $this->belongsTo('App\Arrangement');
    }

    public function arrangeable()
    {
    	return $this->morphTo();
    }

    public function getCostAttribute()
    {
        return $this->quantity * $this->arrangeable->cost;
    }

    public function getPriceAttribute()
    {
        return $this->quantity * $this->arrangeable->price;
    }
}
