<x-layouts.app title="Starter Kit">
    <x-slot:header>
        <x-page-header title="Starter Kit" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Starter Kit'],
        ]" />
    </x-slot:header>

    <div class="row row-cards">
        <div class="col-12">
            <x-card title="Welcome to your Tabler + Laravel starter kit">
                <p class="text-secondary mb-0">
                    This page is a quick reference for the components available in this template. Copy the markup you
                    need into your own pages and remove this page once it's no longer useful.
                </p>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Buttons">
                <div class="btn-list">
                    <x-button color="primary" icon="device-floppy">Primary</x-button>
                    <x-button color="secondary" icon="settings">Secondary</x-button>
                    <x-button color="primary" variant="outline" icon="check">Outline</x-button>
                    <x-button color="primary" variant="ghost" icon="bell">Ghost</x-button>
                    <x-button icon="plus" label="Add" />
                    <x-button color="primary" loading>Loading</x-button>
                    <x-button disabled>Disabled</x-button>
                </div>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Badges">
                <div class="badge-list">
                    <x-badge color="blue" variant="light">Blue</x-badge>
                    <x-badge color="green" variant="light">Green</x-badge>
                    <x-badge color="red" variant="light">Red</x-badge>
                    <x-badge color="purple">Purple</x-badge>
                    <x-badge color="purple" variant="outline">Outline</x-badge>
                    <x-badge color="primary" pill>Pill</x-badge>
                    <x-badge color="green" dot label="Online" />
                </div>
            </x-card>
        </div>

        <div class="col-12">
            <x-card title="Dropdown">
                <div class="btn-list">
                    <x-dropdown trigger-class="btn">
                        <x-slot:trigger>Open dropdown</x-slot:trigger>

                        <x-dropdown-item href="#">Action</x-dropdown-item>
                        <x-dropdown-item href="#">Another action</x-dropdown-item>
                        <div class="dropdown-divider"></div>
                        <x-dropdown-item href="#">Separated link</x-dropdown-item>
                    </x-dropdown>

                    <x-dropdown trigger-class="btn btn-primary" align="end" arrow>
                        <x-slot:trigger>End-aligned, with arrow</x-slot:trigger>

                        <span class="dropdown-header">Section</span>
                        <x-dropdown-item href="#" icon="user" :active="true">Profile</x-dropdown-item>
                        <x-dropdown-item href="#" icon="lock" badge="3">Password</x-dropdown-item>
                        <x-dropdown-item href="#" icon="settings" disabled>Settings</x-dropdown-item>
                    </x-dropdown>

                    <x-dropdown direction="up" trigger-class="btn">
                        <x-slot:trigger>Dropup</x-slot:trigger>

                        <x-dropdown-item href="#">Action</x-dropdown-item>
                        <x-dropdown-item href="#">Another action</x-dropdown-item>
                    </x-dropdown>
                </div>
            </x-card>
        </div>

        <div class="col-12">
            <x-card title="Modal">
                <div class="btn-list">
                    <x-button color="primary" icon="plus" data-bs-toggle="modal" data-bs-target="#modal-report">New report</x-button>
                    <x-button color="danger" variant="outline" icon="alert-triangle" data-bs-toggle="modal" data-bs-target="#modal-delete">Delete item</x-button>
                </div>

                <x-modal id="modal-report" title="New report" size="lg">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" placeholder="Your report name" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Visibility</label>
                        <select class="form-select">
                            <option selected>Private</option>
                            <option>Public</option>
                        </select>
                    </div>

                    <x-slot:footer>
                        <x-button variant="ghost" data-bs-dismiss="modal">Cancel</x-button>
                        <x-button color="primary" icon="plus" class="ms-auto" data-bs-dismiss="modal">Create new report</x-button>
                    </x-slot:footer>
                </x-modal>

                <x-modal id="modal-delete" variant="confirm" status="danger" icon="alert-triangle" title="Are you sure?" size="sm">
                    Do you really want to remove this item? What you've done cannot be undone.

                    <x-slot:footer>
                        <div class="col">
                            <x-button block data-bs-dismiss="modal">Cancel</x-button>
                        </div>
                        <div class="col">
                            <x-button color="danger" block data-bs-dismiss="modal">Delete</x-button>
                        </div>
                    </x-slot:footer>
                </x-modal>
            </x-card>
        </div>

        <div class="col-12">
            <x-card title="Segmented control">
                <x-segmented-control
                    label="Report period"
                    selected="week"
                    :items="[
                        ['label' => 'Day', 'value' => 'day', 'href' => '#'],
                        ['label' => 'Week', 'value' => 'week', 'href' => '#'],
                        ['label' => 'Month', 'value' => 'month', 'href' => '#'],
                        ['label' => 'Year', 'value' => 'year', 'href' => '#', 'disabled' => true],
                    ]"
                    class="mb-3"
                />

                <x-segmented-control
                    label="Section"
                    selected="dashboard"
                    size="sm"
                    :items="[
                        ['label' => 'Dashboard', 'value' => 'dashboard', 'href' => route('dashboard'), 'icon' => 'home'],
                        ['label' => 'Starter Kit', 'value' => 'kit', 'href' => route('starter-kit'), 'icon' => 'rocket'],
                    ]"
                />
            </x-card>
        </div>

        <div class="col-12">
            <x-card title="Alerts">
                <x-alert type="success" title="Wow! Everything worked!">
                    Your account has been saved.
                </x-alert>
                <x-alert type="danger" dismissible>
                    Sorry, there was a problem with your request.
                </x-alert>
                <x-alert type="warning" variant="important" class="mb-0">
                    This is an important alert with a solid background.
                </x-alert>
            </x-card>
        </div>

        <div class="col-12">
            <x-card title="Breadcrumb">
                <x-breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('dashboard')],
                    ['label' => 'Settings'],
                    ['label' => 'Profile'],
                ]" class="mb-2" />
                <x-breadcrumb variant="arrows" :items="[
                    ['label' => 'Dashboard', 'url' => route('dashboard')],
                    ['label' => 'Settings'],
                    ['label' => 'Profile'],
                ]" class="mb-2" />
                <x-breadcrumb variant="dots" muted :items="[
                    ['label' => 'Dashboard', 'url' => route('dashboard')],
                    ['label' => 'Settings'],
                    ['label' => 'Profile'],
                ]" class="mb-0" />
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Avatars">
                <div class="d-flex align-items-center mb-3">
                    <x-avatar size="xs" initials="AB" color="blue" class="me-2" />
                    <x-avatar size="sm" initials="CD" color="green" class="me-2" />
                    <x-avatar initials="EF" color="purple" class="me-2" />
                    <x-avatar size="lg" initials="GH" color="red" class="me-2" />
                    <x-avatar size="xl" initials="IJ" color="yellow" />
                </div>
                <div class="d-flex align-items-center mb-3">
                    <x-avatar src="{{ asset('vendor/tabler/static/avatars/001m.jpg') }}" class="me-2" label="Jane Doe" />
                    <x-avatar src="{{ asset('vendor/tabler/static/avatars/002f.jpg') }}" status="online" class="me-2" label="Online" />
                    <x-avatar initials="JS" shape="square" class="me-2" />
                    <x-avatar icon="user" />
                </div>
                <div class="avatar-list avatar-list-stacked">
                    <x-avatar src="{{ asset('vendor/tabler/static/avatars/003m.jpg') }}" />
                    <x-avatar src="{{ asset('vendor/tabler/static/avatars/004f.jpg') }}" />
                    <x-avatar initials="+8" />
                </div>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="List group">
                <x-list-group transparent class="mb-3">
                    <x-list-group-item href="#" active>Profile</x-list-group-item>
                    <x-list-group-item href="#">Notifications</x-list-group-item>
                    <x-list-group-item href="#">Security</x-list-group-item>
                </x-list-group>

                <x-list-group flush hoverable class="mb-0">
                    <x-list-group-item>
                        <div class="d-flex align-items-center">
                            <x-avatar size="sm" color="warning" icon="alert-triangle" class="me-3" />
                            <div class="flex-fill">
                                <div class="fw-semibold">Users need verification</div>
                                <div class="text-secondary small">3 users pending verification</div>
                            </div>
                            <x-badge color="warning">3</x-badge>
                        </div>
                    </x-list-group-item>
                    <x-list-group-item>
                        <div class="d-flex align-items-center">
                            <x-avatar size="sm" color="green" icon="check" class="me-3" />
                            <div class="flex-fill">
                                <div class="fw-semibold">Backup completed</div>
                                <div class="text-secondary small">Finished a few minutes ago</div>
                            </div>
                        </div>
                    </x-list-group-item>
                </x-list-group>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Form elements">
                <x-input name="demo_text" label="Text input" placeholder="Enter text" icon="search" />
                <x-select name="demo_select" label="Select" :options="['one' => 'Option one', 'two' => 'Option two']" />
                <x-checkbox name="demo_checkbox" checked>Checkbox</x-checkbox>
                <x-checkbox name="demo_switch" switch checked>Switch</x-checkbox>
            </x-card>
        </div>

        <div class="col-12">
            <div class="row row-cards">
                <div class="col-sm-6 col-lg-3">
                    <x-card title="Status strip" status="primary">
                        <p class="text-secondary mb-0">A color strip along the top edge (<code>status</code>).</p>
                    </x-card>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <x-card title="Side status" status="orange" status-position="start">
                        <p class="text-secondary mb-0">Or along the side (<code>status-position="start"</code>).</p>
                    </x-card>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <x-card title="With a footer">
                        <p class="text-secondary mb-0">Content goes in the default slot.</p>

                        <x-slot:footer>
                            <a href="#" class="link-primary">Download PDF</a>
                        </x-slot:footer>
                    </x-card>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <x-card title="Clickable card" href="{{ route('starter-kit') }}" lift>
                        <p class="text-secondary mb-0"><code>href</code> + <code>lift</code> for a hover effect.</p>
                    </x-card>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Table + Pagination</h3>
                    <x-button href="{{ route('starter-kit.users-pdf') }}" target="_blank" color="secondary" variant="outline" icon="download" class="ms-auto">Export PDF</x-button>
                </div>
                <x-table card>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <x-avatar size="sm" :initials="Str::of($user->name)->substr(0, 1)->upper()" class="me-2" />
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td class="text-secondary">{{ $user->email }}</td>
                                <td><x-badge color="green" variant="light">Active</x-badge></td>
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
            </div>
        </div>

        <div class="col-12">
            <x-card title="Roles & permissions">
                <p class="text-secondary">
                    <a href="https://spatie.be/docs/laravel-permission" class="link-primary">spatie/laravel-permission</a> — roles/permissions seeded by <code>RolePermissionSeeder</code>; the demo below reflects the signed-in user (<code>test@example.com</code> is seeded with the <code>admin</code> role).
                </p>

                <div class="mb-3">
                    <div class="text-secondary mb-1">Your roles</div>
                    @forelse (auth()->user()->getRoleNames() as $role)
                        <x-badge color="purple" variant="light" class="me-1">{{ $role }}</x-badge>
                    @empty
                        <span class="text-secondary">No roles assigned.</span>
                    @endforelse
                </div>

                <div class="mb-3">
                    <div class="text-secondary mb-1">Your permissions</div>
                    @forelse (auth()->user()->getAllPermissions() as $permission)
                        <x-badge color="azure" variant="light" class="me-1">{{ $permission->name }}</x-badge>
                    @empty
                        <span class="text-secondary">No permissions.</span>
                    @endforelse
                </div>

                @can('manage users')
                    <x-alert type="success">You can see this because your role has the <code>manage users</code> permission — via <code>@@can('manage users')</code>, which spatie/permission wires into Laravel's <code>Gate</code> automatically.</x-alert>
                @else
                    <x-alert type="warning">You'd need the <code>manage users</code> permission to see a success message here instead.</x-alert>
                @endcan

                <p class="text-secondary mb-2 mt-3">
                    That raw permission check is the quick form. The full pattern — a
                    <code>UserPolicy</code> deciding per model, enforced on the route with
                    <code>#[Authorize]</code> and reflected in the UI with
                    <code>@@can</code> — is in <code>app/Policies/UserPolicy.php</code> and
                    the admin area at <a href="{{ route('admin.users.index') }}" class="link-primary">/admin/users</a>.
                </p>

                @can('viewAny', App\Models\User::class)
                    <x-button href="{{ route('admin.users.index') }}" color="primary" variant="outline" icon="user">Open the admin users page</x-button>
                @endcan
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Progress">
                <div class="mb-3">
                    <x-progress value="38" />
                </div>
                <div class="mb-3">
                    <x-progress value="57" size="sm" color="green" label="57% complete" />
                </div>
                <div class="mb-3">
                    <x-progress value="60" striped animated color="primary" />
                </div>
                <div>
                    <x-progress indeterminate size="sm" />
                </div>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Empty state">
                <x-empty icon="mood-empty" title="No results found" subtitle="Try adjusting your search or filter to find what you're looking for.">
                    <x-slot:actions>
                        <x-button color="primary" icon="plus">Add item</x-button>
                    </x-slot:actions>
                </x-empty>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Tabs">
                <x-tabs
                    selected="home"
                    class="mb-3"
                    :items="[
                        ['label' => 'Home', 'value' => 'home', 'target' => '#tab-home', 'icon' => 'home'],
                        ['label' => 'Settings', 'value' => 'settings', 'target' => '#tab-settings', 'icon' => 'settings'],
                    ]"
                />

                <div class="tab-content">
                    <div class="tab-pane active show" id="tab-home">Home tab content.</div>
                    <div class="tab-pane" id="tab-settings">Settings tab content.</div>
                </div>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Spinners & statuses">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <x-spinner />
                    <x-spinner size="sm" color="primary" />
                    <x-spinner grow color="green" />
                    <x-button color="primary" disabled>
                        <x-spinner size="sm" class="me-2" label="" />
                        Loading&hellip;
                    </x-button>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <x-status color="green">Active</x-status>
                    <x-status color="red" animated>Live</x-status>
                    <x-status color="secondary" lite>Draft</x-status>
                    <x-status color="green" dot label="Online" />
                    <x-status color="azure" indicator animated />
                </div>
            </x-card>
        </div>

        <div class="col-12">
            <x-card title="Toast">
                <div class="toast-container position-static">
                    <x-toast title="Jane Doe" time="11 mins ago">
                        Hello, world! This is a toast message.
                    </x-toast>
                </div>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Tooltip & popover">
                <p class="text-secondary">An existing component just needs the attributes added directly &mdash; no wrapper:</p>
                <div class="btn-list mb-3">
                    <x-button data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top">Hover me</x-button>
                    <x-button data-bs-toggle="tooltip" data-bs-placement="right" title="Tooltip on right">And me</x-button>
                </div>

                <p class="text-secondary"><code>&lt;x-tooltip&gt;</code> is for plain content with no natural host element:</p>
                <p class="mb-3">
                    Status:
                    <x-tooltip text="Deployed 3 minutes ago" placement="top">
                        <x-icon name="check" class="text-success" />
                    </x-tooltip>
                </p>

                <div class="btn-list">
                    <x-popover content="And here's some amazing content. It's very engaging. Right?" title="Popover title">
                        Click to toggle popover
                    </x-popover>
                    <label class="form-label mb-0">
                        ZIP Code
                        <x-popover as="span" class="form-help" content="<p>ZIP Code must be US or CDN format.</p>" html>?</x-popover>
                    </label>
                </div>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Placeholder">
                <x-skeleton>
                    <div class="d-flex align-items-center mb-3">
                        <x-avatar size="lg" class="placeholder me-3" />
                        <div class="flex-fill">
                            <x-placeholder :width="9" class="mb-2" />
                            <x-placeholder :width="7" size="xs" />
                        </div>
                    </div>
                    <x-placeholder :width="12" class="mb-1" />
                    <x-placeholder :width="10" />
                </x-skeleton>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Ribbon">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body" style="height: 5rem"></div>
                            <x-ribbon icon="star" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body" style="height: 5rem"></div>
                            <x-ribbon color="green" bookmark>NEW</x-ribbon>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Carousel">
                <x-carousel id="starter-kit-carousel" :slides="[
                    ['image' => asset('vendor/tabler/static/avatars/001m.jpg'), 'alt' => 'Slide 1'],
                    ['image' => asset('vendor/tabler/static/avatars/002f.jpg'), 'alt' => 'Slide 2'],
                ]" />
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Datepicker">
                <x-datepicker name="demo_date" label="Date" value="2020-06-20" />
                <x-datepicker name="demo_date_icon" label="Date with icon" icon="calendar" value="2020-06-20" />
                <x-datepicker name="demo_date_range" label="Date range" range value="2020-06-20 - 2020-06-25" />
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Inline datepicker">
                <x-datepicker name="demo_date_inline" inline value="2020-06-20" />
            </x-card>
        </div>

        <div class="col-md-6">
            <x-card title="Advanced select">
                <p class="text-secondary">Tabler's "Advanced select" is a plain <code>&lt;select&gt;</code> progressively enhanced by <a href="https://tom-select.js.org" target="_blank" rel="noopener">Tom Select</a> &mdash; searchable, with a custom-rendered dropdown.</p>

                <x-select
                    name="demo_advanced_select"
                    label="Assignee"
                    advanced
                    placeholder="Search people…"
                    :options="$users->mapWithKeys(fn ($user) => [
                        $user->id => [
                            'label' => $user->name,
                            'html' => '<span class=\'avatar avatar-xs\' style=\'background-image: url('.asset('vendor/tabler/static/avatars/00'.($user->id % 5).'m.jpg').')\'></span>',
                        ],
                    ])"
                    class="mb-3"
                />

                <x-select
                    name="demo_advanced_select_tags"
                    label="Tags"
                    advanced
                    multiple
                    placeholder="Add tags…"
                    :options="['html' => 'HTML', 'javascript' => 'JavaScript', 'css' => 'CSS', 'php' => 'PHP', 'laravel' => 'Laravel']"
                    :value="['php', 'laravel']"
                />
            </x-card>
        </div>

        <div class="col-12">
            <x-card title="Calendar">
                <x-calendar
                    id="starter-kit-calendar"
                    height="600"
                    :events="[
                        ['title' => 'Offsite Retreat', 'start' => now()->startOfMonth()->addDays(1)->toDateString(), 'end' => now()->startOfMonth()->addDays(3)->toDateString(), 'color' => 'var(--tblr-red)', 'backgroundColor' => 'var(--tblr-red-lt)', 'borderColor' => 'var(--tblr-red-200)'],
                        ['title' => 'Monthly Planning', 'start' => now()->startOfMonth()->addDays(4)->setTime(10, 0)->toIso8601String(), 'end' => now()->startOfMonth()->addDays(4)->setTime(11, 30)->toIso8601String()],
                        ['title' => 'Design Sprint', 'start' => now()->startOfMonth()->addDays(9)->setTime(9, 0)->toIso8601String(), 'end' => now()->startOfMonth()->addDays(9)->setTime(12, 0)->toIso8601String()],
                        ['title' => 'Company All-Hands', 'start' => now()->startOfMonth()->addDays(17)->setTime(15, 0)->toIso8601String(), 'end' => now()->startOfMonth()->addDays(17)->setTime(16, 0)->toIso8601String()],
                    ]"
                />
            </x-card>
        </div>
    </div>
</x-layouts.app>
