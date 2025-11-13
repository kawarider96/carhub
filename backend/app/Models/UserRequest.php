<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\CarbonImmutable;

/**
 * ───────────────────────────────────────────────────────────
 *  USER REQUEST – Felhasználói kérés (fióktörlés, hiányzó márka)
 * ───────────────────────────────────────────────────────────
 *
 * Ez a tábla tárolja az összes felhasználói igényt,
 * amelyet admin hagy jóvá (DELETE_ACCOUNT, MISSING_BRAND).
 *
 * ───────────────────────────────────────────────────────────
 *  PROPERTIES
 * ───────────────────────────────────────────────────────────
 * @property int                          $id
 * @property int                          $user_id           A kérelmet indító felhasználó
 * @property string                       $type              (DELETE_ACCOUNT | MISSING_BRAND)
 * @property array|null                   $payload           Extra információ (JSON)
 * @property string                       $status            OPEN | APPROVED | REJECTED
 * @property int|null                     $handled_by        Admin azonosító
 * @property CarbonImmutable|null         $handled_at        Mikor kezelte az admin
 * @property CarbonImmutable|null         $created_at
 * @property CarbonImmutable|null         $updated_at
 *
 *
 * ───────────────────────────────────────────────────────────
 *  RELATIONS
 * ───────────────────────────────────────────────────────────
 * @property-read \App\Models\User        $user
 * @property-read \App\Models\User|null   $handler
 *
 */
class UserRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'payload',
        'status',
        'handled_by',
        'handled_at',
    ];

    protected $casts = [
        'payload'     => 'array',
        'handled_at'  => 'immutable_datetime',
        'created_at'  => 'immutable_datetime',
        'updated_at'  => 'immutable_datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Kapcsolatok
    |--------------------------------------------------------------------------
    */

    /**
     * A kérést benyújtó felhasználó.
     *
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Az admin, aki a kérelmet kezelte.
     *
     * @return BelongsTo<User, self>
     */
    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
