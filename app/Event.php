<?php

namespace App;

use App\Proposal;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use Notable;

    protected $casts = [
        'active_proposal_id' => 'integer',
        'customer_id' => 'integer',
        'status_id' => 'integer',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at'
    ];
    protected $hidden = ['account_id'];
    protected $guarded = ['id', 'account_id', 'created_at', 'updated_at'];

    public function setActiveProposal(Proposal $proposal)
    {
        $this->update(['active_proposal_id' => $proposal->id]);
    }

	public function account()
    {
        return $this->belongsTo('App\Account');
    }

    protected function active_proposal()
    {
        return $this->hasOne('App\Proposal', 'id', 'active_proposal_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function proposals()
    {
        return $this->hasMany('App\Proposal');
    }

    public function status()
    {
        return $this->belongsTo('App\EventStatus');
    }
}
