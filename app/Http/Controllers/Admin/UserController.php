<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Spatie\Permission\Models\Role;

/**
 * A worked example of policy-backed authorization.
 *
 * The #[Authorize] attributes apply Laravel's `can` middleware to the action,
 * which resolves the ability against App\Policies\UserPolicy and throws a 403
 * (rendering resources/views/errors/403.blade.php) when it fails. Doing it here
 * rather than in the controller body means the check cannot be forgotten when
 * the method is edited, and it shows up in `php artisan route:list`.
 *
 * Authentication is separate: these routes sit behind the `auth` middleware, so
 * a guest is redirected to login before the policy is ever consulted.
 */
class UserController extends Controller
{
    #[Authorize('viewAny', User::class)]
    public function index(Request $request): View
    {
        $filters = [
            'name' => trim((string) $request->query('name', '')),
            // Cast: the query string is user input, so roles could arrive as a
            // bare string rather than an array.
            'roles' => array_values(array_filter((array) $request->query('roles', []))),
        ];

        // An allow-list rather than passing the column straight to orderBy —
        // the value comes from the query string.
        $sorts = [
            'name' => ['name', 'asc'],
            'name_desc' => ['name', 'desc'],
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
        ];

        $sort = (string) $request->query('sort', 'name');
        [$column, $direction] = $sorts[$sort] ?? $sorts['name'];

        return view('admin.users.index', [
            'filters' => $filters,
            'sort' => $sort,
            'sortOptions' => [
                'name' => 'Name (A–Z)',
                'name_desc' => 'Name (Z–A)',
                'newest' => 'Newest first',
                'oldest' => 'Oldest first',
            ],
            // array_filter drops the empty ones, so this counts how many are
            // actually narrowing the list. Sort is deliberately not counted —
            // it's always set, and it reorders rather than narrows.
            'activeFilterCount' => count(array_filter($filters)),
            'allRoles' => $this->roleOptions(),
            // roles eager-loaded: the table renders a badge per role, which
            // would otherwise be a query per row.
            'users' => User::query()
                ->when($filters['name'] !== '', fn ($query) => $query->whereRaw(
                    "name LIKE ? ESCAPE '!'",
                    ['%'.$this->escapeLike($filters['name']).'%'],
                ))
                ->when($filters['roles'] !== [], fn ($query) => $query->whereHas(
                    'roles',
                    fn ($roles) => $roles->whereIn('name', $filters['roles']),
                ))
                ->with('roles')
                ->orderBy($column, $direction)
                ->paginate(10)
                // Carries the filters and the sort onto the pagination links —
                // without it, paging past page 1 silently drops them all.
                ->withQueryString(),
        ]);
    }

    /**
     * Role names as value => label, for both the filter panel and the edit form.
     *
     * Values stay the raw name — that's what `syncRoles()` and `Rule::exists`
     * expect — while the label is prettied up for display. (Role names happen
     * to be lowercase.)
     *
     * @return array<string, string>
     */
    private function roleOptions(): array
    {
        return Role::orderBy('name')
            ->get()
            ->mapWithKeys(fn (Role $role): array => [$role->name => ucfirst($role->name)])
            ->all();
    }

    /**
     * Escape LIKE's own wildcards, so searching for "50%" matches a literal
     * "50%" rather than everything.
     *
     * Uses "!" rather than the more obvious backslash, and the query has to
     * carry a matching `ESCAPE '!'` clause. Backslash is not portable: MySQL
     * treats it specially inside string literals, so `ESCAPE '\'` means
     * something different there than in SQLite — and SQLite with no ESCAPE
     * clause ignores backslashes entirely, turning a search for "50%" into one
     * that quietly matches nothing. The tests run on SQLite, so this showed up
     * immediately rather than in production.
     */
    private function escapeLike(string $value): string
    {
        return str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $value);
    }

    /**
     * "user" here is the route parameter name, which the middleware resolves to
     * the bound User model before handing it to the policy.
     */
    #[Authorize('create', User::class)]
    public function create(): View
    {
        return view('admin.users.create', [
            'allRoles' => $this->roleOptions(),
        ]);
    }

    #[Authorize('create', User::class)]
    public function store(StoreUserRequest $request): RedirectResponse
    {
        // The password is passed through in plain text: the model casts it as
        // `hashed`, so it's hashed on assignment. Hash::make() here would be
        // redundant (the cast skips an already-hashed value, so it isn't
        // harmful either — just noise).
        $user = User::create($request->safe()->only(['name', 'email', 'password']));

        // The request validates these against the roles table, so syncRoles()
        // can't invent one.
        $user->syncRoles((array) $request->input('roles', []));

        return redirect()
            ->route('admin.users.show', $user)
            ->with('status', 'user-created');
    }

    #[Authorize('view', 'user')]
    public function show(User $user): View
    {
        return view('admin.users.show', ['user' => $user]);
    }

    #[Authorize('update', 'user')]
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'allRoles' => $this->roleOptions(),
        ]);
    }

    #[Authorize('update', 'user')]
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->safe()->only(['name', 'email']));

        // You may not change your own roles: removing your own admin role
        // revokes your access mid-session, and for a sole admin that locks
        // everyone out — the same reason the policy forbids deleting yourself.
        if (! $user->is($request->user())) {
            $user->syncRoles($request->input('roles', []));
        }

        return redirect()
            ->route('admin.users.show', $user)
            ->with('status', 'user-updated');
    }

    #[Authorize('delete', 'user')]
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('status', 'user-deleted');
    }
}
