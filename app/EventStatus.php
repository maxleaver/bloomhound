<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventStatus extends Model
{
    protected $visible = ['name', 'title'];
    public $timestamps = false;

    public function events()
    {
        return $this->hasMany('App\Event', 'status_id');
    }
}
