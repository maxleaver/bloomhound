<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	use Notable, Arrangeable;

	protected $guarded = [];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
