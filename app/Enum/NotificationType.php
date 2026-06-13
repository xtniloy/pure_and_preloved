<?php

namespace App\Enum;

class NotificationType
{
    public const CONTACT = 'contact';
    public const ORDER = 'order';

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
        ];
    }

    public static function label(string $type): string
    {
        return self::all()[$type] ?? ucfirst($type);
    }
}
