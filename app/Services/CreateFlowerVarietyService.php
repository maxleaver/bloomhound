<?php

namespace App\Services;

use App\ArrangeableTypeSetting;
use App\Flower;
use App\FlowerVariety;

class CreateFlowerVarietyService
{
    protected $flower;
    protected $name;

    public function __construct($name, Flower $flower)
    {
        $this->name = $name;
        $this->flower = $flower;
    }

    public function make()
    {
        // Look up default markup setting for flowers
        $setting = ArrangeableTypeSetting::whereAccountId($this->flower->account->id)
            ->whereHas('type', function ($query) {
                return $query->whereName('flower');
            })
            ->first();

        $variety = new FlowerVariety;
        $variety->name = $this->name;
        $variety->arrangeable_type_id = 1;
        $variety->account()->associate($this->flower->account);
        $variety->flower()->associate($this->flower);
        $variety->markup()->associate($setting->markup);
        $variety->markup_value = $setting->markup_value;
        $variety->save();
        return $variety;
    }
}
