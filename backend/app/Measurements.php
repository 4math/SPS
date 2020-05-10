<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Measurements
 *
 * @property int $socket_id
 * @property float $power
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Socket $socket
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Measurements newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Measurements newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Measurements query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Measurements whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Measurements wherePower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Measurements whereSocketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Measurements whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Measurements whereId($value)
 */
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
