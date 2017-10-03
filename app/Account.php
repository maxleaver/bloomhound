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

    public function flower_varieties()
    {
        return $this->hasMany('App\FlowerVariety');
    }

    public function flower_variety_sources()
    {
        return $this->hasMany('App\FlowerVarietySource');
    }

    public function arrangements()
    {
        return $this->hasMany('App\Arrangement');
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function getLogoPathAttribute()
    {
        // Strip /public and append /storage to the image path
        // for external consumption
        return '/storage' . substr($this->logo, 6);
    }
}
