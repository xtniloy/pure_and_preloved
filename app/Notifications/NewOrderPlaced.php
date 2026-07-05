<?php

namespace App\Notifications;

use App\Enum\NotificationType;
use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrderPlaced extends AdminNotification
{
    public function __construct(public Order $order)
    {
    }

    public function notificationType(): string
    {
        return NotificationType::ORDER;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New order ' . $this->order->reference . ' 【' . config('app.name') . '】')
            ->greeting('Hello ' . ($notifiable->name ?? 'Admin'))
            ->line('A new order has been placed on the website.')
            ->line('Reference: ' . $this->order->reference)
            ->line('Customer: ' . $this->order->billing_first_name . ' ' . $this->order->billing_last_name)
            ->line('Total: $' . number_format($this->order->total, 2))
            ->action('View order', route('admin.orders.show', $this->order->id));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => NotificationType::ORDER,
            'icon' => 'cil-basket',
            'color' => 'success',
            'title' => 'New order placed',
            'message' => $this->order->reference . ' · $' . number_format($this->order->total, 2),
            // Relative path: queue workers don't know the request root, so an
            // absolute URL here would be built from APP_URL and can be wrong.
            'url' => route('admin.orders.show', $this->order->id, false),
        ];
    }
}
