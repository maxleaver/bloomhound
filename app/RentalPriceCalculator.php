<?php

namespace App;

class RentalPriceCalculator extends AbstractPriceCalculator
{
    public function calculate()
    {
        return number_format((float)$this->markup_value, 2, '.', '');
    }
}
