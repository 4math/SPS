<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'api_token',
    ];

    protected $hidden = [
        'email', 'password', 'api_token',
    ];
    
    public function sockets()
    {
        return $this->hasMany(Socket::class);
    }
}
