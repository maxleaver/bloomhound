<?php

namespace App\Events;

use App\Account;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AccountCreated
{
    use Dispatchable, SerializesModels;

    public $account;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
}
