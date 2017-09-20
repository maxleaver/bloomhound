<?php

namespace App;

use App\Events\FlowerCreated;
use Illuminate\Database\Eloquent\Model;

class Flower extends Model
{
	use Notable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'flower_library_id', 'account_id',
    ];

    protected $with = ['varieties'];

    protected $dispatchesEvents = [
        'created' => FlowerCreated::class,
    ];

	public function varieties()
    {
        return $this->hasMany('App\FlowerVariety');
    }

    public function library()
    {
    	return $this->belongsTo('App\FlowerLibrary', 'flower_library_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
