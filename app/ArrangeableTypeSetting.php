<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArrangeableTypeSetting extends Model
{
    public $timestamps = false;
    protected $casts = [
        'markup_value' => 'double',
    ];
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function type()
    {
        return $this->belongsTo('App\ArrangeableType', 'arrangeable_type_id', 'id');
    }

    public function markup()
    {
        return $this->belongsTo('App\Markup');
    }
}
