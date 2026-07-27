<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Support\AdminAccess;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminRbacTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Self-contained: make sure the permissions + Super Admin role exist.
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        foreach (AdminAccess::permissions() as $permission) {
            Permission::findOrCreate($permission, AdminAccess::GUARD);
        }
        Role::findOrCreate(AdminAccess::SUPER_ADMIN, AdminAccess::GUARD)
            ->syncPermissions(AdminAccess::permissions());
    }

    private function makeAdmin(): Admin
    {
        $admin = Admin::create([
            'name' => 'RBAC Admin',
            'email' => 'rbac-' . uniqid() . '@example.com',
            'password' => 'secret-password',
            'status' => 1,
        ]);
        $admin->forceFill(['email_verified_at' => now()])->save();

        return $admin;
    }

    private function superAdmin(): Admin
    {
        $admin = $this->makeAdmin();
        $admin->assignRole(AdminAccess::SUPER_ADMIN);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $admin;
    }

    private function adminWithPermissions(array $permissions): Admin
    {
        $admin = $this->makeAdmin();
        $role = Role::create(['name' => 'role-' . uniqid(), 'guard_name' => AdminAccess::GUARD]);
        $role->syncPermissions($permissions);
        $admin->assignRole($role);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $admin;
    }

    public function test_super_admin_can_access_every_module(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin, 'admin')->get(route('admin.orders.index'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.products.index'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.roles.index'))->assertOk();
    }

    public function test_role_and_admin_forms_render(): void
    {
        $admin = $this->superAdmin();
        $role = Role::create(['name' => 'Renderable ' . uniqid(), 'guard_name' => AdminAccess::GUARD]);

        $this->actingAs($admin, 'admin')->get(route('admin.roles.create'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.roles.edit', $role))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.admins.create'))->assertOk()->assertSee('Roles');
        $this->actingAs($admin, 'admin')->get(route('admin.admins.edit', $admin))->assertOk();
    }

    public function test_super_admin_role_is_not_editable(): void
    {
        $admin = $this->superAdmin();
        $superRole = Role::findByName(AdminAccess::SUPER_ADMIN, AdminAccess::GUARD);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.roles.edit', $superRole))
            ->assertRedirect(route('admin.roles.index'));
    }

    public function test_limited_admin_can_access_only_permitted_modules(): void
    {
        $admin = $this->adminWithPermissions(['manage orders']);

        // Allowed
        $this->actingAs($admin, 'admin')->get(route('admin.orders.index'))->assertOk();

        // Denied — no 'manage products' / 'manage roles'
        $this->actingAs($admin, 'admin')->get(route('admin.products.index'))->assertForbidden();
        $this->actingAs($admin, 'admin')->get(route('admin.roles.index'))->assertForbidden();
    }

    public function test_admin_with_no_roles_is_denied(): void
    {
        $admin = $this->makeAdmin(); // no roles

        $this->actingAs($admin, 'admin')->get(route('admin.orders.index'))->assertForbidden();
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get(route('admin.orders.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_helper_reflects_permissions(): void
    {
        $admin = $this->adminWithPermissions(['manage contacts']);
        $this->actingAs($admin, 'admin');

        $this->assertTrue(admin_can('manage contacts'));
        $this->assertFalse(admin_can('manage products'));
    }

    public function test_super_admin_can_create_a_role_with_permissions(): void
    {
        $admin = $this->superAdmin();
        $name = 'Order Staff ' . uniqid();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.roles.store'), [
                'name' => $name,
                'permissions' => ['manage orders', 'manage contacts'],
            ])
            ->assertRedirect(route('admin.roles.index'));

        $role = Role::where('name', $name)->where('guard_name', AdminAccess::GUARD)->first();
        $this->assertNotNull($role);
        $this->assertEqualsCanonicalizing(
            ['manage orders', 'manage contacts'],
            $role->permissions->pluck('name')->all()
        );
    }

    public function test_role_store_rejects_unknown_permissions(): void
    {
        $admin = $this->superAdmin();
        $name = 'Sneaky ' . uniqid();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.roles.store'), [
                'name' => $name,
                'permissions' => ['manage orders', 'drop database'],
            ]);

        $role = Role::where('name', $name)->first();
        $this->assertNotNull($role);
        // Only the known permission is kept.
        $this->assertSame(['manage orders'], $role->permissions->pluck('name')->all());
    }

    public function test_super_admin_role_cannot_be_deleted(): void
    {
        $admin = $this->superAdmin();
        $superRole = Role::findByName(AdminAccess::SUPER_ADMIN, AdminAccess::GUARD);

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.roles.destroy', $superRole))
            ->assertRedirect(route('admin.roles.index'));

        $this->assertDatabaseHas('roles', ['name' => AdminAccess::SUPER_ADMIN]);
    }
}
