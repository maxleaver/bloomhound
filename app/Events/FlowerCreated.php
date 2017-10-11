<?php

namespace App\Events;

use App\Flower;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class FlowerCreated
{
    use Dispatchable, SerializesModels;

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
