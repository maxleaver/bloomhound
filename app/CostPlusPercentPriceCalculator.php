<?php

namespace App;

use App\AbstractPriceCalculator;

class CostPlusPercentPriceCalculator extends AbstractPriceCalculator
{
	public function calculate()
	{
		$price = $this->arrangeable->cost * (1 + $this->markup_value / 100);
		return number_format((float)$price, 2, '.', '');
	}
}
