<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * Class QrToken
 * @property int $id
 * @property int $user_id
 * @property string $auth_token
 * @property string $check_token
 * @property string $access_token
 * @property User $user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Models
 */
class QrToken extends \App\Models\Model
{
    protected $fillable = [
        'auth_token', 'user_id', 'access_token', 'check_token'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $auth_token
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function findByAuthToken($auth_token) {
        return self::query()->where(compact('auth_token'))->first();
    }

    /**
     * @param $check_token
     * @return self|null
     */
    public static function findByCheckToken($check_token) {
        return self::query()->where(compact('check_token'))->first();
    }
}
