<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use Notable;

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $hidden = ['account_id'];
    protected $guarded = ['id', 'account_id', 'created_at', 'updated_at'];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function status()
    {
        return $this->belongsTo('App\EventStatus');
    }

    public function arrangements()
    {
        return $this->hasMany('App\Arrangement');
    }

    public function vendors()
    {
        return $this->belongsToMany('App\Vendor');
    }
}
