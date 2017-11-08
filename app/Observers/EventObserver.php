<?php

namespace App\Observers;

use App\Event;
use App\Proposal;

class EventObserver
{
    /**
     * Listen to the Event created event.
     *
     * @param  Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        $proposal = new Proposal;
        $event->proposals()->save($proposal);
        $event->update(['active_proposal_id' => $proposal->id]);
    }
}
