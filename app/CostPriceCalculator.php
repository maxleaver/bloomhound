<?php

namespace App;

class CostPriceCalculator extends AbstractPriceCalculator
{
    public function calculate()
    {
        return number_format((float)$this->arrangeable->cost, 2, '.', '');
    }
}
