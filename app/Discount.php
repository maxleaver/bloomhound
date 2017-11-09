<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $casts = [
        'amount' => 'float',
        'discountable_id' => 'integer',
    ];
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    /**
     * Get all of the owning discountable models.
     */
    public function discountable()
    {
        return $this->morphTo();
    }
}
