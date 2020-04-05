<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    public function socket()
    {
        return $this->belongsTo('App\Socket');
    }
}
