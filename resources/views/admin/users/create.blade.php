<x-layouts.app title="Add user">
    <x-slot:header>
        <x-page-header title="Add user" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Admin'],
            ['label' => 'Users', 'url' => route('admin.users.index')],
            ['label' => 'Add'],
        ]" />
    </x-slot:header>

    {{-- No row/col wrapper: `.row > *` already gives a lone child width:100%
         and the matching half-gutter padding, so a col-lg-12 round it adds
         nothing at any breakpoint. --}}
    <div class="card">
        <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
            @csrf

            <div class="card-body">
                <x-input name="name" label="Name" autocomplete="name" required autofocus />
                <x-input type="email" name="email" label="Email address" autocomplete="email" required />

                {{-- Same rules as registration — see StoreUserRequest,
                     which reuses Fortify's PasswordValidationRules. --}}
                <x-input type="password" name="password" label="Password"
                         autocomplete="new-password" required help="At least 8 characters." />
                <x-input type="password" name="password_confirmation" label="Confirm password"
                         autocomplete="new-password" required />

                <x-select
                    name="roles"
                    label="Roles"
                    advanced
                    multiple
                    placeholder="Add a role…"
                    :options="$allRoles"
                    class="mb-0"
                />
            </div>

            <div class="card-footer bg-transparent mt-auto">
                <div class="btn-list justify-content-end">
                    <x-button href="{{ route('admin.users.index') }}">Cancel</x-button>
                    <x-button type="submit" color="primary" icon="user-plus">Create user</x-button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
