<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Notifications\ContactMessageReceived;
use App\Services\AdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('public.home.contact');
    }

    public function store(Request $request, AdminNotificationService $notifier): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,filter|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $message = ContactMessage::create($data);

        // Notify admins (email + web) per their preferences. A mail/queue hiccup
        // must not break the visitor's submission, so failures are logged.
        try {
            $notifier->notifyAdmins(new ContactMessageReceived($message));
        } catch (\Throwable $e) {
            Log::error('Contact notification failed: ' . $e->getMessage());
        }

        return redirect()
            ->route('contact.index')
            ->with('success', 'Thank you for reaching out! We have received your message and will get back to you soon.');
    }
}
