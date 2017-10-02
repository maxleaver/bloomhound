<?php

namespace App\Listeners;

use App\FlowerVariety;
use App\Events\FlowerCreated;
use Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $variety = new FlowerVariety;
        $variety->name = 'Default';
        $variety->account()->associate($event->flower->account);
        $variety->flower()->associate($event->flower);
        $variety->save();
    }
}
