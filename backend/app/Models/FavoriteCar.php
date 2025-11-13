<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\CarbonImmutable;

/**
 * ───────────────────────────────────────────────────────────
 *  FAVORITE CAR – Felhasználó kedvenc autója
 * ───────────────────────────────────────────────────────────
 *
 * Egy felhasználó által rögzített kedvenc autó adatai.
 *
 * ───────────────────────────────────────────────────────────
 *  PROPERTIES
 * ───────────────────────────────────────────────────────────
 * @property int                          $id
 * @property int                          $user_id           Felhasználó azonosító
 * @property int                          $car_model_id      Autótípus azonosító
 * @property int|null                     $year              Évjárat
 * @property string|null                  $color             Szín
 * @property string|null                  $fuel              Üzemanyag
 * @property CarbonImmutable|null         $created_at
 * @property CarbonImmutable|null         $updated_at
 *
 *
 * ───────────────────────────────────────────────────────────
 *  RELATIONS
 * ───────────────────────────────────────────────────────────
 * @property-read \App\Models\User        $user
 * @property-read \App\Models\CarModel    $carModel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CarImage[] $images
 *
 */
class FavoriteCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_model_id',
        'year',
        'color',
        'fuel',
    ];

    protected $casts = [
        'year'       => 'integer',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Kapcsolatok
    |--------------------------------------------------------------------------
    */

    /**
     * A felhasználó, akihez ez a kedvenc autó tartozik.
     *
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Az autó típusa.
     *
     * @return BelongsTo<CarModel, self>
     */
    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    /**
     * A kedvenc autóhoz feltöltött képek (1:N).
     *
     * @return HasMany<CarImage>
     */
    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class);
    }
}
