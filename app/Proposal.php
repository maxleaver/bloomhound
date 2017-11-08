<?php

namespace App;

use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use Cloneable;

    protected $casts = [
        'event_id' => 'integer',
        'version' => 'integer',
    ];

    protected $cloneable_relations = ['arrangements', 'deliveries', 'setups', 'vendors'];

    public function arrangements()
    {
        return $this->hasMany('App\Arrangement');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Delivery');
    }

	public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function setups()
    {
        return $this->hasMany('App\Setup');
    }

    public function vendors()
    {
        return $this->belongsToMany('App\Vendor');
    }

    /**
     * Determine if the current proposal is set as the active one.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->event->fresh()->active_proposal_id == $this->id;
    }
    /**
     *
     * Determine if the current proposal is set as the active one.
     *
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        return $this->isActive();
    }
}
