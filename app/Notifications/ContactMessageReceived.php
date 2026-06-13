<?php

namespace App\Notifications;

use App\Enum\NotificationType;
use App\Models\ContactMessage;
use Illuminate\Notifications\Messages\MailMessage;

class ContactMessageReceived extends AdminNotification
{
    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function notificationType(): string
    {
        return NotificationType::CONTACT;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('New contact message from ' . $this->contactMessage->name . ' 【' . config('app.name') . '】')
            ->greeting('Hello ' . ($notifiable->name ?? 'Admin'))
            ->line('You have received a new contact message via the website.')
            ->line('From: ' . $this->contactMessage->name . ' <' . $this->contactMessage->email . '>');

        if ($this->contactMessage->phone) {
            $mail->line('Phone: ' . $this->contactMessage->phone);
        }

        $mail->line('Subject: ' . ($this->contactMessage->subject ?: '—'))
            ->line('Message:')
            ->line($this->contactMessage->message)
            ->action('View in admin panel', route('admin.contact-messages.show', $this->contactMessage->id));

        return $mail;
    }

    /**
     * Stored as the "data" payload for the web (database) notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => NotificationType::CONTACT,
            'icon' => 'cil-envelope-letter',
            'color' => 'info',
            'title' => 'New contact message',
            'message' => $this->contactMessage->name . ' sent a message',
            'url' => route('admin.contact-messages.show', $this->contactMessage->id),
        ];
    }
}
