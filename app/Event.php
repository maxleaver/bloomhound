<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $hidden = ['account_id'];

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

    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }
}
