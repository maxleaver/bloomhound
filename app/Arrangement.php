<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arrangement extends Model
{
	protected $appends = ['cost', 'price', 'total_price'];
    protected $casts = [
        'cost' => 'float',
        'override_price' => 'boolean',
        'price' => 'float',
        'total_price' => 'float',
    ];
    protected $guarded = [];

    public function getCostAttribute()
    {
        return $this->ingredients->sum('cost');
    }

    public function getPriceAttribute()
    {
        if ($this->override_price) {
            return $this->attributes['price'];
        }

        return $this->ingredients->sum('price');
    }

    public function getTotalPriceAttribute()
    {
        return ($this->price * $this->quantity) - $this->percentOff() - $this->amountOff();
    }

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }

    public function discounts()
    {
        return $this->morphMany('App\Discount', 'discountable');
    }

    public function ingredients()
    {
        return $this->hasMany('App\ArrangementIngredient');
    }

    public function proposal()
    {
        return $this->belongsTo('App\Proposal');
    }

    protected function percentOff() {
        $percent = $this->discounts->where('type', 'percent')->sum('amount');
        return ($this->price * $this->quantity) * ($percent / 100);
    }

    protected function amountOff() {
        return $this->discounts->where('type', 'fixed')->sum('amount');
    }
}
