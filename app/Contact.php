<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
	protected $appends = ['name'];
	protected $guarded = ['account_id', 'customer_id'];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function getNameAttribute()
    {
    	return $this->first_name . ' ' . $this->last_name;
    }

    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }
}
