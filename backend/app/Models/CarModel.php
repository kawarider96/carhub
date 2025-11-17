<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\CarbonImmutable;

/**
 * ───────────────────────────────────────────────────────────
 *  CAR MODEL – Autótípus szótár
 * ───────────────────────────────────────────────────────────
 *
 * Egy adott márkához tartozó autótípus (pl. BMW → M3).
 *
 * ───────────────────────────────────────────────────────────
 *  PROPERTIES
 * ───────────────────────────────────────────────────────────
 * @property int                          $id
 * @property int                          $car_brand_id      Hivatkozás a márkára
 * @property string                       $name              Típus neve
 * @property CarbonImmutable|null         $created_at
 * @property CarbonImmutable|null         $updated_at
 *
 *
 * ───────────────────────────────────────────────────────────
 *  RELATIONS
 * ───────────────────────────────────────────────────────────
 * @property-read \App\Models\CarBrand    $brand
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FavoriteCar[] $favoriteCars
 *
 */
class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_brand_id',
        'name'
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
     * A típushoz tartozó márka.
     *
     * @return BelongsTo<CarBrand, CarModel>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    /**
     * Kedvenc autók, ahol ezt a típust választották.
     *
     * @return HasMany<FavoriteCar, CarModel>
     */
    public function favoriteCars(): HasMany
    {
        return $this->hasMany(FavoriteCar::class);
    }
}
