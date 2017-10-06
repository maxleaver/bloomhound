<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlowerVariety extends Model
{
	use Arrangeable;

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
    protected $fillable = ['name', 'best_price_id'];

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

    public function getIngredientNameAttribute()
    {
        return $this->flower->name . ' - ' . $this->name;
    }

    public function best_source()
    {
        return $this->belongsTo('App\FlowerVarietySource', 'best_price_id');
    }

    public function type()
    {
        return $this->belongsTo('App\ArrangeableType', 'arrangeable_type_id', 'id');
    }

    public function getBestPrice()
    {
        return $this->sources->sortBy('cost_per_stem')->first();
    }

    public function markBestPrice(FlowerVarietySource $source)
    {
        $this->update(['best_price_id' => $source->id]);
    }
}
