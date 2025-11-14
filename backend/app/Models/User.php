<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Carbon\CarbonImmutable;
use Laravel\Sanctum\HasApiTokens;

/**
 * ───────────────────────────────────────────────────────────
 *  USER MODEL – Részletes dokumentációval
 * ───────────────────────────────────────────────────────────
 *
 *  A rendszer felhasználóit reprezentálja (admin + user).
 *
 * ───────────────────────────────────────────────────────────
 *  PROPERTIES (adatbázis oszlopok)
 * ───────────────────────────────────────────────────────────
 * @property int                          $id
 * @property string|null                  $full_name         Teljes név
 * @property string                       $username          Felhasználónév (egyedi)
 * @property string                       $password          Hashelt jelszó
 * @property string                       $role              admin | user
 * @property bool                         $is_active         Aktív státusz
 * @property int                          $failed_logins     Sikertelen próbálkozások száma
 * @property CarbonImmutable|null         $created_at
 * @property CarbonImmutable|null         $updated_at
 *
 *
 * ───────────────────────────────────────────────────────────
 *  RELATIONS (read-only)
 * ───────────────────────────────────────────────────────────
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FavoriteCar[]  $favoriteCars
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserRequest[]  $requests
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserRequest[]  $handledRequests
 *
 *
 * ───────────────────────────────────────────────────────────
 *  CUSTOM HELPERS
 * ───────────────────────────────────────────────────────────
 * @property-read bool                    $is_admin
 * @property-read bool                    $is_locked
 *
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'username',
        'password',
        'role',
        'is_active',
        'failed_logins',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'created_at'  => 'immutable_datetime',
        'updated_at'  => 'immutable_datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Kapcsolatok
    |--------------------------------------------------------------------------
    */

    /**
     * Felhasználó kedvenc autói.
     *
     * @return HasMany<FavoriteCar, User>
     */
    public function favoriteCars(): HasMany
    {
        return $this->hasMany(FavoriteCar::class);
    }

    /**
     * Felhasználó által indított kérelmek.
     *
     * @return HasMany<UserRequest, User>
     */
    public function requests(): HasMany
    {
        return $this->hasMany(UserRequest::class);
    }

    /**
     * Kérelmek, amelyeket ez a felhasználó (admin) kezelt.
     *
     * @return HasMany<UserRequest, User>
     */
    public function handledRequests(): HasMany
    {
        return $this->hasMany(UserRequest::class, 'handled_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper metódusok
    |--------------------------------------------------------------------------
    */

    /**
     * Megmondja, hogy a felhasználó admin-e.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * A felhasználó zárolt-e pillanatnyilag.
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        return !$this->is_active;
    }

    /**
     * Jelszó automatikus hash-elése.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        // ne hashelje újra
        if (strlen($value) === 60 && str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = $value;
            return;
        }

        $this->attributes['password'] = bcrypt($value);
    }

}
