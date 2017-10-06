<?php

namespace App\Services;

use App\Flower;
use App\FlowerVariety;

class CreateFlowerVarietyService
{
    protected $flower;
    protected $name;

    function __construct($name, Flower $flower) {
        $this->name = $name;
        $this->flower = $flower;
    }

    public function make()
    {
        $variety = new FlowerVariety;
        $variety->name = $this->name;
        $variety->arrangeable_type_id = 1;
        $variety->account()->associate($this->flower->account);
        $variety->flower()->associate($this->flower);
        $variety->save();
        return $variety;
    }
}
