<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socket extends Model
{

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function data()
    {
        return $this->hasMany('App\Data');
    }
}
