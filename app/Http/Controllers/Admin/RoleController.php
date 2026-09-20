<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Spatie\Permission\Models\Role;

/**
 * Read-only. Roles are fixed (created by RoleSeeder for KMS; 'superadmin' by
 * the starter-kit's own seeder) — there is no role/permission CRUD here,
 * only an overview and a per-role user list.
 *
 * Gated by the same ability as the Users screen (App\Policies\UserPolicy's
 * viewAny, i.e. the 'view admin panel' permission): Access Control is one
 * admin-only section, not two separately-permissioned ones.
 */
class RoleController extends Controller
{
    #[Authorize('viewAny', User::class)]
    public function index(): View
    {
        return view('admin.roles.index', [
            'roles' => Role::withCount('users')->orderBy('name')->get(),
        ]);
    }

    #[Authorize('viewAny', User::class)]
    public function show(Role $role): View
    {
        return view('admin.roles.show', [
            'role' => $role->loadCount('users'),
            'permissions' => $role->permissions()->orderBy('name')->get(),
            'users' => $role->users()->orderBy('name')->paginate(10),
        ]);
    }
}
