<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    // Use HasApiTokens trait only if Sanctum is available
    //use \Laravel\Sanctum\HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'active_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active_until' => 'datetime',
    ];

    /**
     * Determine if the user is active.
     *
     * @return bool
     */
    public function isActive()
    {
        // Si el usuario está marcado como inactivo, retornar false
        if (!$this->active) {
            return false;
        }

        // Si hay una fecha de expiración y ya pasó, retornar false
        if ($this->active_until && Carbon::now()->greaterThan($this->active_until)) {
            return false;
        }

        return true;
    }

    /**
     * Get the remaining active days.
     *
     * @return int|null
     */
    public function getRemainingDays()
    {
        if (!$this->active_until) {
            return null;
        }

        $now = Carbon::now();
        if ($now->greaterThan($this->active_until)) {
            return 0;
        }

        return $now->diffInDays($this->active_until);
    }

    /**
     * Get the active status label.
     *
     * @return string
     */
    public function getActiveStatusLabel()
    {
        if (!$this->active) {
            return 'Inactivo';
        }

        if ($this->active_until) {
            if (Carbon::now()->greaterThan($this->active_until)) {
                return 'Expirado';
            }
            return 'Activo hasta ' . $this->active_until->format('d/m/Y');
        }

        return 'Activo';
    }
}
