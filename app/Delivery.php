<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
	protected $casts = [
        'fee' => 'double',
    ];
    protected $dates = [
        'created_at',
        'deliver_on',
        'updated_at'
    ];
	protected $guarded = ['account_id', 'event_id'];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function arrangements()
    {
        return $this->hasMany('App\Arrangement');
    }

    public function proposal()
    {
        return $this->belongsTo('App\Proposal');
    }
}
