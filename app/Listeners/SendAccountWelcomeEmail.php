<?php

namespace App\Listeners;

use App\Events\AccountRegistered;
use App\Mail\NewAccountWelcome;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendAccountWelcomeEmail implements ShouldQueue
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
     * @param  AccountRegistered  $event
     * @return void
     */
    public function handle(AccountRegistered $event)
    {
        Mail::to($event->user->email)->send(new NewAccountWelcome());
    }
}
