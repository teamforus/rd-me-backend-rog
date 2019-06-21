<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Validation
 * @property int $id
 * @property int $user_id
 * @property int $user_record_id
 * @property string $uuid
 * @property string $state
 * @property UserRecord $record
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property
 * @package App\Models
 */
class Validation extends \App\Models\Model
{
    const STATE_PENDING = 'pending';
    const STATE_APPROVED = 'approved';
    const STATE_DECLINED = 'declined';

    const STATES = [
        self::STATE_PENDING,
        self::STATE_APPROVED,
        self::STATE_DECLINED,
    ];

    protected $fillable = [
        'user_id', 'user_record_id', 'uuid', 'state', 'identity_address'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function record() {
        return $this->belongsTo(UserRecord::class, 'user_record_id');
    }

    /**
     * @param null $uuid
     * @return self|null
     */
    public static function findByUuid($uuid = null) {
        return self::query()->where(compact('uuid'))->first();
    }
}
