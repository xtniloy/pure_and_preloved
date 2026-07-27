<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Support\AdminAccess;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Idempotent: safe to re-run whenever the module/permission list changes.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 1. Ensure every module permission exists (on the admin guard).
        foreach (AdminAccess::permissions() as $permission) {
            Permission::findOrCreate($permission, AdminAccess::GUARD);
        }

        // 2. Super Admin role gets every permission (belt-and-suspenders — it
        //    also bypasses checks via Gate::before).
        $superAdmin = Role::findOrCreate(AdminAccess::SUPER_ADMIN, AdminAccess::GUARD);
        $superAdmin->syncPermissions(AdminAccess::permissions());

        // 3. Assign Super Admin to every existing admin so nobody is locked out.
        Admin::query()->each(function (Admin $admin) use ($superAdmin) {
            if (! $admin->hasRole($superAdmin)) {
                $admin->assignRole($superAdmin);
            }
        });
    }
}
