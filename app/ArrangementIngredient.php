<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArrangementIngredient extends Model
{
    protected $appends = ['cost', 'price'];
    protected $casts = [
        'cost' => 'float',
        'price' => 'float',
    ];
    protected $guarded = [];
    protected $with = ['arrangeable'];

    public function arrangement()
    {
        return $this->belongsTo('App\Arrangement');
    }

    public function arrangeable()
    {
        return $this->morphTo();
    }

    public function getCostAttribute()
    {
        return $this->quantity * $this->arrangeable->cost;
    }

    public function getPriceAttribute()
    {
        return $this->quantity * $this->arrangeable->price;
    }
}
