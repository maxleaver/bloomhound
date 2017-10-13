<?php

namespace App;

use App\AbstractArrangeable;

class FlowerVariety extends AbstractArrangeable
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'account_id',
        'flower_id'
    ];
    protected $appends = ['ingredient_name'];
    protected $guarded = ['account_id', 'arrangeable_type_id'];
    protected $casts = [
        'use_default_markup' => 'boolean',
    ];

    public function sources()
    {
        return $this->hasMany('App\FlowerVarietySource');
    }

	public function flower()
    {
        return $this->belongsTo('App\Flower');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function getCostAttribute()
    {
        if (count($this->best_source()->first()) > 0) {
            return $this->best_source()->first()->cost_per_stem;
        }

        return 0;
    }

    public function getIngredientNameAttribute()
    {
        return $this->flower->name . ' - ' . $this->name;
    }

    public function best_source()
    {
        return $this->belongsTo('App\FlowerVarietySource', 'best_price_id');
    }

    public function getBestPrice()
    {
        return $this->sources->sortBy('cost_per_stem')->first();
    }

    public function setBestPrice()
    {
        $bestSource = $this->getBestPrice();
        $this->update(['best_price_id' => $bestSource->id]);
    }
}
