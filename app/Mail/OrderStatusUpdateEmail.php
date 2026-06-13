<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        $app = config('app.name');
        $ref = $this->order->reference;

        $subject = match ($this->order->status) {
            'shipped'   => 'Your order ' . $ref . ' has shipped 【' . $app . '】',
            'completed' => 'Your order ' . $ref . ' is complete 【' . $app . '】',
            'cancelled' => 'Your order ' . $ref . ' has been cancelled 【' . $app . '】',
            'paid'      => 'Payment received for order ' . $ref . ' 【' . $app . '】',
            default     => 'Update on your order ' . $ref . ' 【' . $app . '】',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.order_status_update_email',
            with: ['order' => $this->order],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
