<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlowerVarietySource extends Model
{
    protected $casts = [
        'cost' => 'float',
        'stems_per_bunch' => 'integer',
    ];

	public function variety()
    {
        return $this->belongsTo('App\FlowerVariety', 'flower_variety_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    public function account()
    {
    	return $this->belongsTo('App\Account');
    }
}
