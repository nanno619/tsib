<x-layouts.app title="Sunting {{ $user->name }}">
    <x-slot:header>
        <x-page-header title="Sunting pengguna" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Pentadbiran'],
            ['label' => 'Pengguna', 'url' => route('admin.users.index')],
            ['label' => $user->name, 'url' => route('admin.users.show', $user)],
            ['label' => 'Sunting'],
        ]" />
    </x-slot:header>

    {{-- No row/col wrapper: `.row > *` already gives a lone child width:100%
         and the matching half-gutter padding, so a col-lg-12 round it adds
         nothing at any breakpoint. --}}
    <div class="card">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="card-body">
                <x-input name="name" label="Nama" :value="$user->name" autocomplete="name" required />
                <x-input type="email" name="email" label="Alamat e-mel" :value="$user->email" autocomplete="email" required />

                @if ($user->is(auth()->user()))
                    {{-- Same reasoning as the policy's self-delete guard:
                         dropping your own admin role revokes your access
                         mid-session, and a sole admin would lock everyone
                         out. The controller ignores roles for self too —
                         this only explains why the field is absent. --}}
                    <x-alert type="info" class="mb-0">
                        Anda tidak boleh menukar peranan anda sendiri. Minta pentadbir lain melakukannya.
                    </x-alert>
                @else
                    <x-select
                        name="roles"
                        label="Peranan"
                        multiple
                        advanced
                        placeholder="Tambah peranan…"
                        :options="$allRoles"
                        :value="$user->getRoleNames()->all()"
                        class="mb-0"
                    />
                @endif
            </div>

            <div class="card-footer bg-transparent mt-auto">
                <div class="btn-list justify-content-end">
                    <x-button href="{{ route('admin.users.show', $user) }}">Batal</x-button>
                    <x-button type="submit" color="primary" icon="device-floppy">Simpan perubahan</x-button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
