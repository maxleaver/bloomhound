<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use Notable;

    protected $guarded = ['id', 'account_id', 'created_at', 'updated_at'];

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function proposals()
    {
        return $this->belongsToMany('App\Proposal');
    }
}
