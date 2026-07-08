<?php

namespace App\Enum;

class NotificationType
{
    public const CONTACT = 'contact';
    public const ORDER = 'order';
    public const BLOG_COMMENT = 'blog_comment';

    /**
     * All notification types keyed by value, with a human label.
     *
     * @return array<string, string>
     */
    public static function all(): array
    {
        return [
            self::CONTACT => 'Contact messages',
            self::ORDER => 'New orders',
            self::BLOG_COMMENT => 'Blog comments',
        ];
    }

    public static function label(string $type): string
    {
        return self::all()[$type] ?? ucfirst($type);
    }
}
