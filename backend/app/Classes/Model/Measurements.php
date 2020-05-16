<?php

namespace App\Classes\Model;

use Illuminate\Database\Eloquent\Model;

class Measurements extends Model
{
    protected $fillable = [
        'power',
    ];
    
    public function socket()
    {
        return $this->belongsTo(Socket::class);
    }
}
