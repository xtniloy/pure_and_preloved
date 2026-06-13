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

    public static array $files_status = [
        1 => 'Active',
        0 => 'Restricted',
    ];

    public static array $files_status_code = [
        'Active'=> 1,
        'Restricted' => 0,
    ];

    public static array $gender_meta = [
        'man'    => ['label' => 'Mens Category',   'icon' => 'cil-user',        'desc' => 'Manage all categories for men'],
        'women'  => ['label' => 'Womens Category', 'icon' => 'cil-user-female', 'desc' => 'Manage all categories for women'],
        'unisex' => ['label' => 'Unisex Category', 'icon' => 'cil-people',      'desc' => 'Categories available to everyone'],
    ];


}

General::initializeRoles();
