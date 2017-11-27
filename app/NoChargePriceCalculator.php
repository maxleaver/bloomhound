<?php

namespace App;

class NoChargePriceCalculator extends AbstractPriceCalculator
{
    public function calculate()
    {
        return number_format(0, 2, '.', '');
    }
}
