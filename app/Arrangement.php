<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arrangement extends Model
{
	protected $guarded = [];
    protected $appends = ['cost', 'default_price'];

    public function getCostAttribute()
    {
        return $this->ingredients->sum('cost');
    }

    public function getDefaultPriceAttribute()
    {
        return $this->ingredients->sum('price');
    }

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
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
