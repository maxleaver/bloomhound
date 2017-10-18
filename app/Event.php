<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use Notable;

    protected $dates = [
        'date',
        'created_at',
        'updated_at'
    ];
    protected $hidden = ['account_id'];
    protected $guarded = ['id', 'account_id', 'created_at', 'updated_at'];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function arrangements()
    {
        return $this->hasMany('App\Arrangement');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Delivery');
    }

    public function setups()
    {
        return $this->hasMany('App\Setup');
    }

    public function status()
    {
        return $this->belongsTo('App\EventStatus');
    }

    public function vendors()
    {
        return $this->belongsToMany('App\Vendor');
    }
}
