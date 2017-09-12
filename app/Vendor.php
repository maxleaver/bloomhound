<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }
}
