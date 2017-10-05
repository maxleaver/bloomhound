<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	use Notable;

    protected $guarded = ['customer_id', 'account_id', 'created_at', 'updated_at'];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }
}
