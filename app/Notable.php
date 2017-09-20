<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Notable
{
	public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }
}
