<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserRecord
 * @property int $id
 * @property string $key
 * @property string $value
 * @property integer $order
 * @property User $user
 * @property Collection|Validation[] $validations
 * @property Collection|Validation[] $validations_approved
 * @property Collection|Validation[] $validations_declined
 * @property Collection|Validation[] $validations_pending
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Models
 */
class UserRecord extends \App\Models\Model
{
    protected $fillable = [
        'key', 'value', 'order',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function validations() {
        return $this->hasMany(Validation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function validations_approved() {
        return $this->hasMany(Validation::class)->where([
            'state' => Validation::STATE_APPROVED
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function validations_declined() {
        return $this->hasMany(Validation::class)->where([
            'state' => Validation::STATE_DECLINED
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function validations_pending() {
        return $this->hasMany(Validation::class)->where([
            'state' => Validation::STATE_PENDING
        ]);
    }
}
