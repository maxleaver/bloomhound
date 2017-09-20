<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlowerVariety extends Model
{
	/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'flower_id'
    ];

	public function flower()
    {
        return $this->belongsTo('App\Flower');
    }
}
