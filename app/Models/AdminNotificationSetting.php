<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'type',
        'mail',
        'web',
    ];

    protected $casts = [
        'mail' => 'boolean',
        'web' => 'boolean',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
