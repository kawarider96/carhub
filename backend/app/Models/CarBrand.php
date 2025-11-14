<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\CarbonImmutable;

/**
 * ───────────────────────────────────────────────────────────
 *  CAR BRAND MODEL – Autómárka szótár
 * ───────────────────────────────────────────────────────────
 *
 *  A rendszerben elérhető autómárkák listája.
 *
 * ───────────────────────────────────────────────────────────
 *  PROPERTIES
 * ───────────────────────────────────────────────────────────
 * @property int                          $id
 * @property string                       $name              Márka neve (pl. BMW, Audi, Toyota)
 * @property CarbonImmutable|null         $created_at
 * @property CarbonImmutable|null         $updated_at
 *
 *
 * ───────────────────────────────────────────────────────────
 *  RELATIONS
 * ───────────────────────────────────────────────────────────
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CarModel[] $models
 *
 */
class CarBrand extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

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
     * A márkához tartozó autótípusok (1:N).
     *
     * @return HasMany<CarModel, CarBrand>
     */
    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class);
    }
}
