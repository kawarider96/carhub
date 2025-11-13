<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\CarbonImmutable;

/**
 * ───────────────────────────────────────────────────────────
 *  CAR IMAGE – Kedvenc autóhoz tartozó kép
 * ───────────────────────────────────────────────────────────
 *
 * Binárisan tárolt autókép + MIME típus.
 *
 * ───────────────────────────────────────────────────────────
 *  PROPERTIES
 * ───────────────────────────────────────────────────────────
 * @property int                          $id
 * @property int                          $favorite_car_id   Kedvenc autó azonosító
 * @property string                       $content           BLOB tartalom
 * @property string|null                  $mime              MIME típus (pl. image/jpeg)
 * @property CarbonImmutable|null         $created_at
 * @property CarbonImmutable|null         $updated_at
 *
 *
 * ───────────────────────────────────────────────────────────
 *  RELATIONS
 * ───────────────────────────────────────────────────────────
 * @property-read \App\Models\FavoriteCar $favoriteCar
 *
 */
class CarImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'favorite_car_id',
        'content',
        'mime',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Kapcsolatok
    |--------------------------------------------------------------------------
    */

    /**
     * A képhez tartozó kedvenc autó.
     *
     * @return BelongsTo<FavoriteCar, self>
     */
    public function favoriteCar(): BelongsTo
    {
        return $this->belongsTo(FavoriteCar::class);
    }
}
