<?php

namespace App;

use App\AbstractArrangeable;
use Illuminate\Database\Eloquent\Model;

class Item extends AbstractArrangeable
{
	use Notable;

	protected $guarded = [];
	protected $appends = ['ingredient_name'];
    protected $casts = [
        'use_default_markup' => 'boolean',
    ];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function getIngredientNameAttribute()
    {
        return $this->name;
    }
}
