<?php

namespace App;

use App\Events\AccountCreated;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name', 'address', 'website', 'email', 'phone', 'logo'
    ];
    protected $appends = ['logo_path'];
    protected $dispatchesEvents = [
        'created' => AccountCreated::class,
    ];

    public function arrangements()
    {
        return $this->hasMany('App\Arrangement');
    }

    public function arrangeable_type_settings()
    {
        return $this->hasMany('App\ArrangeableTypeSetting');
    }

    public function customers()
    {
        return $this->hasMany('App\Customer');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Delivery');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
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

    public function invitations()
    {
        return $this->hasMany('App\Invite');
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function settings()
    {
        return $this->hasOne('App\AccountSetting');
    }

    public function setups()
    {
        return $this->hasMany('App\Setup');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function vendors()
    {
        return $this->hasMany('App\Vendor');
    }

    public function getLogoPathAttribute()
    {
        if ($this->logo) {
            // Strip /public and append /storage to the image path
            // for external consumption
            return '/storage' . substr($this->logo, 6);
        }

        return null;
    }
}
