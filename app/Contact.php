<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
	protected $guarded = ['account_id', 'customer_id'];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
}
