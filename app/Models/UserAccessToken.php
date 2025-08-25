<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccessToken extends Model
{
//    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'email',
        'user_id',
        'token',
        'otp',
        'use_for',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->otp = mt_rand(100000, 999999);
        });
    }

    public function uniqueIds(): array
    {
        return ['token'];
    }

    public function getRouteKeyName():string
    {
        return 'token';
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'email','email');
    }
}
