<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	use Notable, Arrangeable;

	protected $guarded = [];
	protected $appends = ['ingredient_name'];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function type()
    {
        return $this->belongsTo('App\ArrangeableType', 'arrangeable_type_id', 'id');
    }

    public function getIngredientNameAttribute()
    {
        return $this->name;
    }
}
