<?php

namespace App;

use Exception;
use ReflectionClass;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractArrangeable extends Model
{
    public function markup()
    {
        return $this->belongsTo('App\Markup');
    }

    public function type()
    {
        return $this->belongsTo('App\ArrangeableType', 'arrangeable_type_id', 'id');
    }

    public function markup_setting()
    {
        return $this->belongsTo('App\ArrangeableTypeSetting', 'arrangeable_type_id');
    }

    public function used()
    {
        return $this->morphMany('App\ArrangementIngredient', 'arrangeable');
    }

    public function getMarkupValue()
    {
        return $this->markup_value;
    }

    public function getPriceAttribute()
    {
        return $this->calculatePrice($this);
    }

    public function getArrangeableTypeAttribute()
    {
        $reflect = new ReflectionClass($this);
        return strtolower($reflect->getShortName());
    }

    protected function calculatePrice()
    {
        switch ($this->markup->name) {
            case 'no_charge':
                $calculator = new \App\NoChargePriceCalculator($this);
                break;
            case 'cost':
                $calculator = new \App\CostPriceCalculator($this);
                break;
            case 'cost_plus_percent':
                $calculator = new \App\CostPlusPercentPriceCalculator($this);
                break;
            case 'cost_plus_amount':
                $calculator = new \App\CostPlusAmountPriceCalculator($this);
                break;
            case 'fixed_price':
                $calculator = new \App\FixedPriceCalculator($this);
                break;
            case 'amount_times_event_days':
                $calculator = new \App\RentalPriceCalculator($this);
                break;
            default:
                throw new Exception('Could not find price calculator for ' . $this->markup->name);
                return;
        }

        return $calculator->calculate();
    }

    /**
     * Appends an entry to the Eloquent $appends array
     * @param  array  $values
     * @return array
     */
    protected function getArrayableItems(array $values)
    {
        if (!in_array('arrangeable_type', $this->appends)) {
            $this->appends[] = 'arrangeable_type';
        }

        if (!in_array('price', $this->appends)) {
            $this->appends[] = 'price';
        }

        return parent::getArrayableItems($values);
    }
}
