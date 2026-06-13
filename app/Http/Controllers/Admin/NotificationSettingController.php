<?php

namespace App\Http\Controllers\Admin;

use App\Enum\NotificationType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminNotificationSetting;
use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
    public function index()
    {
        $admins = Admin::with('notificationSettings')->orderBy('name')->get();
        $types = NotificationType::all();

        return view('admin.sections.settings.notifications', compact('admins', 'types'));
    }

    public function update(Request $request)
    {
        $prefs = $request->input('prefs', []);
        $types = array_keys(NotificationType::all());

        foreach (Admin::all() as $admin) {
            foreach ($types as $type) {
                AdminNotificationSetting::updateOrCreate(
                    ['admin_id' => $admin->id, 'type' => $type],
                    [
                        'mail' => (bool) data_get($prefs, "{$admin->id}.{$type}.mail"),
                        'web' => (bool) data_get($prefs, "{$admin->id}.{$type}.web"),
                    ]
                );
            }
        }

        return redirect()->route('admin.settings.notifications')->with('success', 'Notification settings saved.');
    }
}
