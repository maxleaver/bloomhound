<?php

namespace App\Listeners;

use App\Events\AccountCreated;

class CreateDefaultArrangeableTypeSettings
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
        $records = [];

        $account = $event->account;
        $markup = \App\Markup::whereName('no_charge')->first();
        $types = \App\ArrangeableType::all();

        foreach ($types as $type) {
            $setting = [
                'account_id' => $account->id,
                'arrangeable_type_id' => $type->id,
                'markup_id' => $markup->id
            ];
            array_push($records, $setting);
        }

        \App\ArrangeableTypeSetting::insert($records);
    }
}
