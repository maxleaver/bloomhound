<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlowerVariety extends Model
{
	use Arrangeable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'flower_id'
    ];

    public function sources()
    {
        return $this->hasMany('App\FlowerVarietySource');
    }

	public function flower()
    {
        return $this->belongsTo('App\Flower');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
