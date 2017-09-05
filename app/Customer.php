<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }
}
