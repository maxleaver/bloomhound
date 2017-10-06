<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArrangeableType extends Model
{
	public $timestamps = false;
    protected $hidden = ['model'];

	public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function flower_varieties()
    {
        return $this->hasMany('App\FlowerVariety');
    }
}
