<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::guard('admin')->user()
            ->notifications()
            ->paginate(20);

        return view('admin.sections.notifications.index', compact('notifications'));
    }

    /**
     * Mark a single notification as read, then redirect to its target URL.
     */
    public function read(string $id)
    {
        $admin = Auth::guard('admin')->user();
        $notification = $admin->notifications()->findOrFail($id);

        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        return $url ? redirect($url) : redirect()->route('admin.notifications.index');
    }

    public function readAll()
    {
        Auth::guard('admin')->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
