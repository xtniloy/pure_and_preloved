<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * Base class for notifications sent to admins. The set of channels is resolved
 * per-admin from their notification preferences (mail / web), defaulting to ON.
 *
 * Queued (via the configured queue connection) so requests like the contact
 * form return immediately instead of waiting on SMTP. Requires a running
 * worker: `php artisan queue:work`.
 */
abstract class AdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The NotificationType value this notification belongs to (e.g. 'contact').
     */
    abstract public function notificationType(): string;

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];

        if (method_exists($notifiable, 'notificationEnabled')) {
            if ($notifiable->notificationEnabled($this->notificationType(), 'mail')) {
                $channels[] = 'mail';
            }
            if ($notifiable->notificationEnabled($this->notificationType(), 'web')) {
                $channels[] = 'database';
            }

            return $channels;
        }

        // Fallback for notifiables without preferences.
        return ['mail', 'database'];
    }
}
