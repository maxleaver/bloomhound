<?php

namespace App\Observers;

use App\FlowerVarietySource;

class FlowerVarietySourceObserver
{
    /**
     * Listen to the FlowerVarietySource saving event.
     *
     * @param  FlowerVarietySource  $source
     * @return void
     */
    public function saving(FlowerVarietySource $source)
    {
        $source->cost_per_stem = $source->cost / $source->stems_per_bunch;
    }

    /**
     * Listen to the FlowerVarietySource saved event
     *
     * @param  FlowerVarietySource $source
     * @return void
     */
    public function saved(FlowerVarietySource $source)
    {
        $source->variety->setBestPrice();
    }

    /**
     * Listen to the FlowerVarietySource deleted event.
     *
     * @param  FlowerVarietySource  $source
     * @return void
     */
    public function deleted(FlowerVarietySource $source)
    {
        $source->variety->fresh()->setBestPrice();
    }
}
