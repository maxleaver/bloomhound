<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	use Notable;

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

    public function events()
    {
        return $this->hasMany('App\Event');
    }
}
