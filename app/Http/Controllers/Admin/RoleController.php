<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Support\AdminAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::where('guard_name', AdminAccess::GUARD)
            ->withCount('permissions')
            ->orderBy('name')
            ->get();

        // Number of admins holding each role (for the list).
        $adminCounts = [];
        foreach ($roles as $role) {
            $adminCounts[$role->id] = Admin::role($role)->count();
        }

        return view('admin.sections.roles.index', compact('roles', 'adminCounts'));
    }

    public function create(): View
    {
        return view('admin.sections.roles.form', [
            'role'     => null,
            'modules'  => AdminAccess::modules(),
            'assigned' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $role = Role::create(['name' => $data['name'], 'guard_name' => AdminAccess::GUARD]);
        $role->syncPermissions($this->cleanPermissions($request->input('permissions', [])));

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): View|RedirectResponse
    {
        if ($this->isSuperAdmin($role)) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'The Super Admin role has full access and cannot be edited.');
        }

        return view('admin.sections.roles.form', [
            'role'     => $role,
            'modules'  => AdminAccess::modules(),
            'assigned' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        if ($this->isSuperAdmin($role)) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'The Super Admin role cannot be modified.');
        }

        $data = $this->validated($request, $role);

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($this->cleanPermissions($request->input('permissions', [])));

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($this->isSuperAdmin($role)) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'The Super Admin role cannot be deleted.');
        }

        if (Admin::role($role)->exists()) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'This role is assigned to one or more admins. Reassign them before deleting it.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    private function validated(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('roles', 'name')
                    ->where(fn ($q) => $q->where('guard_name', AdminAccess::GUARD))
                    ->ignore($role?->id),
            ],
            'permissions'   => ['array'],
            'permissions.*' => ['string'],
        ]);
    }

    /** Keep only known permissions so arbitrary strings can't be injected. */
    private function cleanPermissions(array $permissions): array
    {
        return array_values(array_intersect($permissions, AdminAccess::permissions()));
    }

    private function isSuperAdmin(Role $role): bool
    {
        return $role->name === AdminAccess::SUPER_ADMIN;
    }
}
