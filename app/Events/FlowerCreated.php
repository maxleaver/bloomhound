<?php

namespace App\Events;

use App\Flower;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class FlowerCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $flower;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Flower $flower)
    {
        $this->flower = $flower;
    }
}
