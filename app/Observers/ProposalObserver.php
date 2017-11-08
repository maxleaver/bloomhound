<?php

namespace App\Observers;

use App\Proposal;

class ProposalObserver
{
    /**
     * Listen to the Proposal creating event.
     *
     * @param  Proposal  $proposal
     * @return void
     */
    public function creating(Proposal $proposal)
    {
        $latest = $proposal->event
            ->proposals()
            ->orderBy('id', 'desc')
            ->first();

        $proposal->version = $latest ? $latest->version + 1 : 1;
    }

    /**
     * Listen to the Proposal created event
     *
     * @param  Proposal $proposal
     * @return void
     */
    public function created(Proposal $proposal)
    {
        $proposal->event->setActiveProposal($proposal);
    }
}
