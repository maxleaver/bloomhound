<?php

namespace App;

use App\AbstractPriceCalculator;

class FixedPriceCalculator extends AbstractPriceCalculator
{
	public function calculate()
	{
		return number_format((float)$this->markup_value, 2, '.', '');
	}
}
