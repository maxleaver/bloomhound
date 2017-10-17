<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountSetting extends Model
{
	protected $guarded = ['account_id'];
	protected $primaryKey = 'account_id';
	protected $casts = [
        'use_tax' => 'boolean',
        'tax_amount' => 'double',
    ];

	public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
