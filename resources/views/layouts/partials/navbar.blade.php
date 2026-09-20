{{-- Top navbar: brand, theme toggle, notifications, user menu --}}
<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle primary navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <x-brand class="pe-0 pe-md-3" />

        <div class="navbar-nav flex-row order-md-last">
            <div class="d-none d-md-flex me-3">
                <div class="nav-item">
                    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <x-icon name="moon" />
                    </a>
                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <x-icon name="sun" />
                    </a>
                </div>
            </div>

            <x-dropdown trigger-class="nav-link px-0" :caret="false" label="Show notifications" align="end" arrow card auto-close="outside" class="nav-item d-none d-md-flex me-3">
                <x-slot:trigger>
                    <x-icon name="bell" />
                </x-slot:trigger>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Notifications</h3>
                    </div>
                    <div class="list-group list-group-flush list-group-hoverable">
                        <div class="list-group-item">
                            <div class="text-secondary">You're all caught up. New notifications will show up here.</div>
                        </div>
                    </div>
                </div>
            </x-dropdown>

            <x-dropdown trigger-class="nav-link d-flex lh-1 p-0 px-2" :caret="false" label="Open user menu" align="end" arrow class="nav-item">
                <x-slot:trigger>
                    <x-avatar size="sm" :src="auth()->user()?->getFirstMediaUrl('avatar')" :initials="Str::of(auth()->user()->name ?? 'U')->substr(0, 1)->upper()" :label="auth()->user()->name ?? 'Guest'" />
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ auth()->user()->name ?? 'Guest' }}</div>
                        <div class="mt-1 small text-secondary">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                </x-slot:trigger>

                <x-dropdown-item href="{{ route('settings.profile') }}" icon="user">Profile</x-dropdown-item>
                <x-dropdown-item href="{{ route('settings.password') }}" icon="lock">Password</x-dropdown-item>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" novalidate>
                    @csrf
                    <x-dropdown-item type="submit" icon="logout">Sign out</x-dropdown-item>
                </form>
            </x-dropdown>
        </div>
    </div>
</header>

{{-- Primary navigation row: Dashboard / Administrations / Starter Kit --}}
<div class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <div class="row flex-column flex-md-row flex-fill align-items-center">
                    <div class="col">
                        <nav aria-label="Primary">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                        <span class="nav-link-icon"><x-icon name="home" /></span>
                                        <span class="nav-link-title">Dashboard</span>
                                    </a>
                                </li>
                                <x-dropdown as="li" trigger-class="nav-link" auto-close="outside" class="nav-item">
                                    <x-slot:trigger>
                                        <span class="nav-link-icon"><x-icon name="users" /></span>
                                        <span class="nav-link-title">Staff Management</span>
                                    </x-slot:trigger>

                                    <x-dropdown direction="end" trigger-class="dropdown-item" auto-close="outside">
                                        <x-slot:trigger>
                                            <x-icon name="user" class="dropdown-item-icon" />
                                            Staff
                                        </x-slot:trigger>

                                        <x-dropdown-item href="#">Active</x-dropdown-item>
                                        <x-dropdown-item href="#">Resigned</x-dropdown-item>
                                        <x-dropdown-item href="#">Add Staff</x-dropdown-item>
                                    </x-dropdown>
                                    <x-dropdown direction="end" trigger-class="dropdown-item" auto-close="outside">
                                        <x-slot:trigger>
                                            <x-icon name="calendar" class="dropdown-item-icon" />
                                            Leave
                                        </x-slot:trigger>

                                        <x-dropdown-item href="#">Leave History</x-dropdown-item>
                                        <x-dropdown-item href="#">Leave Application</x-dropdown-item>
                                        <x-dropdown-item href="#">Apply Leave</x-dropdown-item>
                                    </x-dropdown>
                                    <x-dropdown-item href="#" icon="currency-dollar">Payslip</x-dropdown-item>
                                    <x-dropdown-item href="#" icon="clock">Schedule</x-dropdown-item>
                                </x-dropdown>
                                <x-dropdown as="li" trigger-class="nav-link" auto-close="outside" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                    <x-slot:trigger>
                                        <span class="nav-link-icon"><x-icon name="settings" /></span>
                                        <span class="nav-link-title">Administrations</span>
                                    </x-slot:trigger>

                                    <x-dropdown-item href="#" icon="shield">Access Control</x-dropdown-item>
                                    <x-dropdown-item href="#" icon="bell">Announcement</x-dropdown-item>
                                    <x-dropdown-item href="#" icon="history">Audit Trail</x-dropdown-item>
                                    <x-dropdown-item href="#" icon="book">Data Dictionary</x-dropdown-item>
                                    <x-dropdown-item href="#" icon="calendar">Holiday</x-dropdown-item>
                                    <x-dropdown direction="end" trigger-class="dropdown-item" auto-close="outside">
                                        <x-slot:trigger>
                                            <x-icon name="tool" class="dropdown-item-icon" />
                                            Maintenance
                                        </x-slot:trigger>

                                        <x-dropdown-item href="#">Backup Files</x-dropdown-item>
                                        <x-dropdown-item href="#">Log Viewer</x-dropdown-item>
                                    </x-dropdown>
                                    {{-- Nested submenu, demonstrating a multi-level dropdown --}}
                                    <x-dropdown direction="end" trigger-class="dropdown-item" auto-close="outside">
                                        <x-slot:trigger>Preferences</x-slot:trigger>

                                        <x-dropdown-item href="#">Notifications</x-dropdown-item>
                                        <x-dropdown-item href="#">Appearance</x-dropdown-item>
                                    </x-dropdown>
                                    <x-dropdown-item href="#" icon="adjustments">System Setting</x-dropdown-item>
                                    <x-dropdown-item href="#" icon="trash">Trash Bin</x-dropdown-item>
                                    {{-- Policy-driven navigation: only rendered for
                                         users the UserPolicy lets into the admin
                                         area at all. --}}
                                    @can('viewAny', App\Models\User::class)
                                        <x-dropdown-item href="{{ route('admin.users.index') }}" icon="user" :active="request()->routeIs('admin.users.*')">Users</x-dropdown-item>
                                    @endcan
                                </x-dropdown>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <span class="nav-link-icon"><x-icon name="chart-bar" /></span>
                                        <span class="nav-link-title">Reports</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('starter-kit') ? 'active' : '' }}" href="{{ route('starter-kit') }}">
                                        <span class="nav-link-icon"><x-icon name="rocket" /></span>
                                        <span class="nav-link-title">Starter Kit</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
