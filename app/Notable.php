<?php

namespace App;

trait Notable
{
    public function notes()
    {
        return $this->morphMany('App\Note', 'notable');
    }
}
