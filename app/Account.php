<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name', 'address', 'website', 'email', 'phone', 'logo'
    ];
    protected $appends = ['logo_path'];

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

    public function vendors()
    {
        return $this->hasMany('App\Vendor');
    }

    public function flowers()
    {
        return $this->hasMany('App\Flower');
    }

    public function arrangements()
    {
        return $this->hasMany('App\Arrangement');
    }

    public function flower_variety_sources()
    {
        return $this->hasMany('App\FlowerVarietySource');
    }

    public function getLogoPathAttribute()
    {
        // Strip /public from the image path for external consumption
        return substr($this->logo, 6);
    }
}
