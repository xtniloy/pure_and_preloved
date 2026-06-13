<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * All valid order statuses.
     */
    public const STATUSES = [
        'pending',
        'processing',
        'paid',
        'shipped',
        'completed',
        'cancelled',
    ];

    /**
     * The linear fulfilment progression shown in the customer status tracker
     * ("cancelled" is a terminal state handled separately).
     */
    public const PROGRESS_STATUSES = [
        'pending',
        'processing',
        'paid',
        'shipped',
        'completed',
    ];

    protected $fillable = [
        'user_id',
        'reference',
        'total',
        'subtotal',
        'status',
        'shipping_method',
        'shipping_charge',
        'billing_first_name',
        'billing_last_name',
        'billing_address',
        'billing_city',
        'billing_postcode',
        'billing_country',
        'billing_phone',
        'billing_email',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Bootstrap contextual colour for the current status (badges, etc.).
     */
    public function getStatusColorAttribute(): string
    {
        return [
            'pending' => 'secondary',
            'processing' => 'info',
            'paid' => 'primary',
            'shipped' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
        ][$this->status] ?? 'secondary';
    }
}
