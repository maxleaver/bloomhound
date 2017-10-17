<?php

namespace App\Listeners;

use App\AccountSetting;
use App\Events\AccountCreated;

class CreateAccountSettings
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
     * @param  \App\Events\AccountCreated  $event
     * @return void
     */
    public function handle(AccountCreated $event)
    {
        $setting = new AccountSetting;
        $setting->use_tax = false;
        $setting->tax_amount = null;

        $event->account->settings()->save($setting);
    }
}
