<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
	protected $fillable = [
	    'email'
	];

	public function getUrlAttribute()
	{
		return route('invite', ['token' => $this->token]);
	}

	protected static function boot()
	{
	    parent::boot();

	    static::creating(function($invite) {
            do {
	    		$token = str_random(16);
			} while (Invite::where('token', $token)->first() instanceof Invite);

			$invite->token = $token;
        });

	}
}
