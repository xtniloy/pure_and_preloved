<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Roles & permissions live on the "admin" guard (admins authenticate via
     * the admin guard, not the default web guard).
     */
    protected string $guard_name = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
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
        'password' => 'hashed',
    ];

    public function adminAccessToken(): HasOne
    {
        return $this->hasOne(AdminAccessToken::class);
    }

    public function notificationSettings(): HasMany
    {
        return $this->hasMany(AdminNotificationSetting::class);
    }

    /**
     * Whether this admin should receive a given notification type on a channel.
     * Defaults to ON (opt-out) when no preference row exists yet.
     *
     * @param  string  $type     A NotificationType value (e.g. 'contact', 'order')
     * @param  string  $channel  'mail' or 'web'
     */
    public function notificationEnabled(string $type, string $channel): bool
    {
        $setting = $this->notificationSettings->firstWhere('type', $type);

        if (!$setting) {
            return true; // opt-out default: notify unless explicitly disabled
        }

        return $channel === 'mail' ? (bool) $setting->mail : (bool) $setting->web;
    }
}
