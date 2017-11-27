<?php

namespace App;

use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use Cloneable;

    protected $appends = ['subtotal', 'tax', 'total'];

    protected $casts = [
        'event_id' => 'integer',
        'version' => 'integer',
    ];

    protected $cloneable_relations = ['arrangements', 'deliveries', 'setups', 'vendors'];

    public function getSubtotalAttribute()
    {
        $subtotal = $this->arrangements->sum('total_price');
        $subtotal += $this->deliveries->sum('fee');
        $subtotal += $this->setups->sum('fee');

        $percentDiscount = $this->discounts->where('type', 'percent')->sum('amount');
        $fixedDiscount = $this->discounts->where('type', 'fixed')->sum('amount');

        $subtotal -= $subtotal * ($percentDiscount / 100);
        $subtotal -= $fixedDiscount;

        return $subtotal;
    }

    public function getTaxAttribute()
    {
        $settings = $this->event->account->settings;

        if ($settings->use_tax) {
            return $this->subtotal * ($settings->tax_amount / 100);
        }

        return 0;
    }

    public function getTotalAttribute()
    {
        return $this->subtotal + $this->tax;
    }

    public function arrangements()
    {
        return $this->hasMany('App\Arrangement');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Delivery');
    }

    public function discounts()
    {
        return $this->morphMany('App\Discount', 'discountable');
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
