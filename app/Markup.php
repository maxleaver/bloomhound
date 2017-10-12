<?php

namespace App;

use App\ArrangeableInterface;
use Illuminate\Database\Eloquent\Model;

class Markup extends Model
{
	public $timestamps = false;

	public function items()
	{
		return $this->hasMany('App\Item');
	}

	public function flower_varieties()
	{
		return $this->hasMany('App\FlowerVariety');
	}

	public function settings()
    {
        return $this->hasMany('App\ArrangeableTypeSetting');
    }
}
