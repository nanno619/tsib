{{--
    A worked example of policy-backed authorization in Blade.

    @can('delete', $user) asks App\Policies\UserPolicy — the same rule the
    controller enforces via #[Authorize] — so the button is hidden for users who
    would get a 403 anyway. A hidden button is *not* the security boundary: the
    policy on the route is. This just avoids offering an action that can't work.
--}}
<x-layouts.app title="Users">
    <x-slot:header>
        <x-page-header title="Users" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Admin'],
            ['label' => 'Users'],
        ]">
            <x-slot:actions>
                {{-- Gated by the same policy the route enforces, so an editor
                     isn't offered a button that would 403 anyway. --}}
                @can('create', App\Models\User::class)
                    <x-button href="{{ route('admin.users.create') }}" color="primary" icon="user-plus">Add user</x-button>
                @endcan
            </x-slot:actions>
        </x-page-header>
    </x-slot:header>

    @if (session('status') === 'user-deleted')
        <x-alert type="success" class="mb-3">The user has been deleted.</x-alert>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All users</h3>

            {{-- Tinted whenever a filter is narrowing the list, so the panel
                 isn't invisible state. The label carries the count for screen
                 readers, which can't see the tint. --}}
            <x-button
                class="ms-auto"
                :color="$activeFilterCount ? 'primary' : null"
                :variant="$activeFilterCount ? null : 'ghost'"
                icon="search"
                :label="$activeFilterCount
                    ? 'Filter users ('.$activeFilterCount.' active)'
                    : 'Filter users'"
                data-bs-toggle="offcanvas"
                data-bs-target="#users-filter"
                aria-controls="users-filter"
            />
        </div>

        @if ($users->isEmpty())
            <div class="card-body">
                @if ($activeFilterCount)
                    <x-empty icon="search" title="No matching users" subtitle="Nothing matches those filters — try widening them.">
                        <x-slot:actions>
                            <x-button href="{{ route('admin.users.index') }}">Reset filters</x-button>
                        </x-slot:actions>
                    </x-empty>
                @else
                    <x-empty icon="user" title="No users yet" subtitle="New accounts will appear here." />
                @endif
            </div>
        @else
            <x-table card>
                <thead>
                    <tr>
                        {{-- Actions first. The heading is empty visually but
                             labelled for screen readers, which would otherwise
                             announce a column with no name. --}}
                        <th class="w-1"><span class="visually-hidden">Actions</span></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                {{-- Bare icon actions, not buttons — see the
                                     .action-icon rule in resources/css/app.scss.

                                     Tooltips come from Tabler's own page-load
                                     initialiser, which scans for
                                     [data-bs-toggle="tooltip"] and reads
                                     data-bs-placement — no wrapper component
                                     needed. The aria-label stays: it carries
                                     the full name, which the short tooltip text
                                     doesn't, and it survives Bootstrap
                                     temporarily removing `title` while the
                                     tooltip is showing. --}}
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="action-icon"
                                       aria-label="View {{ $user->name }}"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                        <x-icon name="eye" />
                                    </a>

                                    @can('update', $user)
                                        <a href="{{ route('admin.users.edit', $user) }}" class="action-icon"
                                           aria-label="Edit {{ $user->name }}"
                                           data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                            <x-icon name="pencil" />
                                        </a>
                                    @endcan

                                    @can('delete', $user)
                                        {{-- The confirm dialog comes from
                                             resources/js/confirm.js — see
                                             <x-confirm-modal /> in the layout.

                                             The inline onsubmit is the no-JS
                                             fallback; the module strips it on
                                             load so the native prompt and the
                                             modal never both appear. --}}
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                              novalidate
                                              data-confirm-delete
                                              data-confirm-title="Delete user"
                                              data-confirm-message="{{ $user->name }} ({{ $user->email }}) will be removed from the user list."
                                              onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="action-icon action-icon-danger"
                                                    aria-label="Delete {{ $user->name }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                <x-icon name="trash" />
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <x-avatar
                                        size="sm"
                                        :src="$user->getFirstMediaUrl('avatar')"
                                        :initials="Str::of($user->name)->substr(0, 1)->upper()"
                                        :label="$user->name"
                                        class="me-2"
                                    />
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td class="text-secondary">{{ $user->email }}</td>
                            <td>
                                @forelse ($user->roles as $role)
                                    <x-badge color="purple" variant="light">{{ $role->name }}</x-badge>
                                @empty
                                    <span class="text-secondary">&mdash;</span>
                                @endforelse
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>

            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-secondary">
                    Showing <span>{{ $users->firstItem() }}</span> to <span>{{ $users->lastItem() }}</span> of <span>{{ $users->total() }}</span> entries
                </p>
                <x-pagination :paginator="$users" class="m-0 ms-auto" />
            </div>
        @endif
    </div>

    {{-- Filter panel. Rendered outside the card so nothing in the card's layout
         can clip a fixed-position drawer.

         A plain GET form, so the filters end up in the URL: the result is
         shareable, the back button works, and the paginator's
         withQueryString() carries them onto every page link. --}}
    <x-offcanvas id="users-filter" title="Filter users">
        <form method="GET" action="{{ route('admin.users.index') }}" id="users-filter-form" novalidate>
            <x-input name="name" label="Name" :value="$filters['name']"
                     placeholder="Search by name…" icon="search" />

            {{-- Tom Select needs no special handling in a drawer:
                 resources/js/select.js sets dropdownParent: 'body', so the menu
                 is appended to <body> and positioned over the panel rather than
                 being clipped by .offcanvas-body's overflow. --}}
            <x-select
                name="roles"
                label="Roles"
                advanced
                multiple
                placeholder="Any role"
                :options="$allRoles"
                :value="$filters['roles']"
            />

            {{-- Plain, not `advanced`: four fixed options don't need search or
                 custom rendering, and the native control is keyboard- and
                 screen-reader-native for free. --}}
            <x-select name="sort" label="Sort by" :value="$sort" :options="$sortOptions" class="mb-0" />
        </form>

        <x-slot:footer>
            <div class="btn-list justify-content-end">
                <x-button href="{{ route('admin.users.index') }}">Reset</x-button>
                {{-- The footer sits outside the body, so this points back at the
                     form by id rather than nesting inside it. --}}
                <x-button type="submit" form="users-filter-form" color="primary" icon="check">Apply</x-button>
            </div>
        </x-slot:footer>
    </x-offcanvas>
</x-layouts.app>
