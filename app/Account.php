<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name'
    ];

    public function users()
    {
    	return $this->hasMany('App\User');
    }

    public function invitations()
    {
    	return $this->hasMany('App\Invite');
    }

    public function customers()
    {
        return $this->hasMany('App\Customer');
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
