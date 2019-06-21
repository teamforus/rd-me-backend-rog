<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class Model
 * @property int $id
 * @property string $email
 * @property string $address
 * @property Collection|UserRecord[] $records
 * @method static static find($id, $columns = ['*'])
 * @method static static make($attributes = array())
 * @method static static create($attributes = array())
 * @method static static findOrFail($id, $columns = array())
 * @method static static findOrNew($id, $columns = array())
 * @method static static firstOrNew($id, $columns = array())
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function records() {
        return $this->hasMany(UserRecord::class);
    }
}
