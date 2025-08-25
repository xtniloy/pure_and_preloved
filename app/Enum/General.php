<?php

namespace App\Enum;

class General
{

    public static array $roles;

    public static function initializeRoles()
    {
        self::$roles = array_flip((new \ReflectionClass(\App\Enum\Role::class))->getConstants());
    }


    public static array $user_status = [
        '1' => 'Active',
        '0' => 'Deactive',
    ];


    public static array $access_token_types =[
        'email_verification' => 1,
        'password_update' => 2,
    ];


    public static array $sort_by = [
        1 => 'asc',
        2 => 'desc'
    ];

    public static array $chat_status = [
        1 => 'Active',
        0 => 'Closed'
    ];


}

General::initializeRoles();
