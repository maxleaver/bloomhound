<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

trait Arrangeable
{
	public function used()
    {
        return $this->morphMany('App\ArrangementIngredient', 'arrangeable');
    }

    public function getArrangeableTypeAttribute()
    {
        $reflect = new ReflectionClass($this);
        return strtolower($reflect->getShortName());
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

        return parent::getArrayableItems($values);
    }
}
