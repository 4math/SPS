<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Data
 *
 * @property int $socket_id
 * @property float $power
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Socket $socket
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Data newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Data newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Data query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Data whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Data wherePower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Data whereSocketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Data whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Data extends Model
{
    protected $fillable = [
        'power',
    ];
    
    public function socket()
    {
        return $this->belongsTo(Socket::class);
    }
}
