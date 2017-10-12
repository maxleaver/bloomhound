<?php

namespace App;

use App\AbstractPriceCalculator;

class NoChargePriceCalculator extends AbstractPriceCalculator
{
	public function calculate()
	{
		return number_format(0, 2, '.', '');
	}
}
