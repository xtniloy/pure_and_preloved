<?php

namespace App\Support;

/**
 * Single source of truth for admin (staff) access control.
 *
 * Each "module" maps a section of the admin panel to one permission. This is
 * used by the roles seeder, the role management UI (permission checkboxes),
 * the route middleware and the sidebar visibility checks — so permissions
 * only ever need to be defined in one place.
 */
class AdminAccess
{
    /** The guard admins authenticate on (roles/permissions are scoped to it). */
    public const GUARD = 'admin';

    /** The role that bypasses every permission check. */
    public const SUPER_ADMIN = 'Super Admin';

    /**
     * module key => [label, permission]
     *
     * The permission string is what gets stored, checked in middleware
     * (`permission:manage orders,admin`) and rendered in the role form.
     */
    public static function modules(): array
    {
        return [
            'users'             => ['label' => 'Customers',         'permission' => 'manage users'],
            'admins'            => ['label' => 'Admins / Staff',    'permission' => 'manage admins'],
            'roles'             => ['label' => 'Roles & Access',    'permission' => 'manage roles'],
            'orders'            => ['label' => 'Orders',            'permission' => 'manage orders'],
            'categories'        => ['label' => 'Categories',        'permission' => 'manage categories'],
            'products'          => ['label' => 'Products',          'permission' => 'manage products'],
            'featured_products' => ['label' => 'Featured Products', 'permission' => 'manage featured products'],
            'shipping_methods'  => ['label' => 'Shipping Methods',  'permission' => 'manage shipping methods'],
            'homepage'          => ['label' => 'Homepage & Hero',   'permission' => 'manage homepage'],
            'footer'            => ['label' => 'Footer',            'permission' => 'manage footer'],
            'social_links'      => ['label' => 'Social Links',      'permission' => 'manage social links'],
            'pages'             => ['label' => 'CMS Pages',         'permission' => 'manage pages'],
            'blog'              => ['label' => 'Blog',              'permission' => 'manage blog'],
            'contacts'          => ['label' => 'Contact Messages',  'permission' => 'manage contacts'],
            'settings'          => ['label' => 'Settings',          'permission' => 'manage settings'],
        ];
    }

    /** Flat list of every permission name. */
    public static function permissions(): array
    {
        return array_values(array_map(fn ($m) => $m['permission'], self::modules()));
    }
}
