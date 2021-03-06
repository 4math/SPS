<?php

namespace App\Classes\Model;

use Illuminate\Database\Eloquent\Model;

class Socket extends Model
{
    protected $fillable = [
        'name', 'description', 'user_id',
    ];

    protected $hidden = [
        'user_id',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function data()
    {
        return $this->hasMany(Measurements::class);
    }
}
