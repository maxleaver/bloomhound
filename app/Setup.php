<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
	protected $casts = [
        'fee' => 'double',
    ];
    protected $dates = [
        'created_at',
        'setup_on',
        'updated_at'
    ];
	protected $guarded = ['account_id', 'event_id'];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
