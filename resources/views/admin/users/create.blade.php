<x-layouts.app title="Tambah pengguna">
    <x-slot:header>
        <x-page-header title="Tambah pengguna" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Pentadbiran'],
            ['label' => 'Pengguna', 'url' => route('admin.users.index')],
            ['label' => 'Tambah'],
        ]" />
    </x-slot:header>

    {{-- No row/col wrapper: `.row > *` already gives a lone child width:100%
         and the matching half-gutter padding, so a col-lg-12 round it adds
         nothing at any breakpoint. --}}
    <div class="card">
        <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
            @csrf

            <div class="card-body">
                <x-input name="name" label="Nama" autocomplete="name" required autofocus />
                <x-input type="email" name="email" label="Alamat e-mel" autocomplete="email" required />

                {{-- Same rules as registration — see StoreUserRequest,
                     which reuses Fortify's PasswordValidationRules. --}}
                <x-input type="password" name="password" label="Kata laluan"
                         autocomplete="new-password" required help="Sekurang-kurangnya 8 aksara." />
                <x-input type="password" name="password_confirmation" label="Sahkan kata laluan"
                         autocomplete="new-password" required />

                <x-select
                    name="roles"
                    label="Peranan"
                    advanced
                    multiple
                    placeholder="Tambah peranan…"
                    :options="$allRoles"
                    class="mb-0"
                />
            </div>

            <div class="card-footer bg-transparent mt-auto">
                <div class="btn-list justify-content-end">
                    <x-button href="{{ route('admin.users.index') }}">Batal</x-button>
                    <x-button type="submit" color="primary" icon="user-plus">Cipta pengguna</x-button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
