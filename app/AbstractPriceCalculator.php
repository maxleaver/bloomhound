<?php

namespace App;

abstract class AbstractPriceCalculator
{
    protected $arrangeable;
    protected $markup_value;

    public function __construct(AbstractArrangeable $arrangeable)
    {
        $this->arrangeable = $arrangeable;

        if ($this->arrangeable->use_default_markup) {
            // Use account setting markup value
            $this->markup_value = $this->arrangeable->markup_setting->markup_value;
        } else {
            // Use arrangeables markup value
            $this->markup_value = $this->arrangeable->markup_value;
        }
    }

    abstract public function calculate();
}
