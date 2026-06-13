<?php

namespace App\Services;

use App\Models\Admin;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

/**
 * Central place to dispatch notifications to admins. Each admin's enabled
 * channels (mail / web) are resolved inside the notification's via() based on
 * their saved preferences, so this just decides WHO is considered.
 */
class AdminNotificationService
{
    public function notifyAdmins(AdminNotification $notification): void
    {
        // Eager-load preferences to avoid N+1 inside via()/notificationEnabled().
        $admins = Admin::with('notificationSettings')->get();

        if ($admins->isEmpty()) {
            return;
        }

        NotificationFacade::send($admins, $notification);
    }
}
