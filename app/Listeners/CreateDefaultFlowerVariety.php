<?php

namespace App\Listeners;

use App\Events\FlowerCreated;
use App\Services\CreateFlowerVarietyService;

class CreateDefaultFlowerVariety
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FlowerCreated  $event
     * @return void
     */
    public function handle(FlowerCreated $event)
    {
        (new CreateFlowerVarietyService('Default', $event->flower))->make();
    }
}
