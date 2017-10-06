<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
	public $timestamps = false;

	public function items()
    {
        return $this->hasMany('App\Item');
    }
}
