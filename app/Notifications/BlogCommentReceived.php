<?php

namespace App\Notifications;

use App\Enum\NotificationType;
use App\Models\BlogComment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class BlogCommentReceived extends AdminNotification
{
    public function __construct(public BlogComment $comment)
    {
    }

    public function notificationType(): string
    {
        return NotificationType::BLOG_COMMENT;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $post = $this->comment->post;
        $author = $this->comment->user->name ?? 'A customer';

        return (new MailMessage)
            ->subject('New blog comment on "' . ($post->title ?? 'a post') . '" 【' . config('app.name') . '】')
            ->greeting('Hello ' . ($notifiable->name ?? 'Admin'))
            ->line($author . ' commented on the blog post "' . ($post->title ?? '') . '".')
            ->line('Comment:')
            ->line(Str::limit($this->comment->body, 500))
            ->action('Manage comments', route('admin.blog-comments.index', ['post' => $this->comment->blog_post_id]));
    }

    /**
     * Stored as the "data" payload for the web (database) notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => NotificationType::BLOG_COMMENT,
            'icon' => 'cil-comment-square',
            'color' => 'info',
            'title' => 'New blog comment',
            'message' => ($this->comment->user->name ?? 'A customer') . ' commented on "' . Str::limit($this->comment->post->title ?? '', 60) . '"',
            // Relative path: queue workers don't know the request root, so an
            // absolute URL here would be built from APP_URL and can be wrong.
            'url' => route('admin.blog-comments.index', ['post' => $this->comment->blog_post_id], false),
        ];
    }
}
