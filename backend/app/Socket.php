<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Socket
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property int $switch_state
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Data[] $data
 * @property-read int|null $data_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Socket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Socket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Socket query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Socket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Socket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Socket whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Socket whereSwitchState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Socket whereUserId($value)
 * @mixin \Eloquent
 */
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
