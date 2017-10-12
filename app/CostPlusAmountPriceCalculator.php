<?php

namespace App;

use App\AbstractPriceCalculator;

class CostPlusAmountPriceCalculator extends AbstractPriceCalculator
{
	public function calculate()
	{
		$price = $this->arrangeable->cost + $this->markup_value;
		return number_format((float)$price, 2, '.', '');
	}
}
