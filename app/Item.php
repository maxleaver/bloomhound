<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	use Notable;

	protected $guarded = [];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
