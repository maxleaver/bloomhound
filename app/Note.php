<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'notable_id', 'notable_type',
    ];

    protected $with = ['user'];

    public function user()
	{
		return $this->belongsTo('App\User');
	}

	/**
     * Get all of the owning notable models.
     */
    public function notable()
    {
        return $this->morphTo();
    }
}
