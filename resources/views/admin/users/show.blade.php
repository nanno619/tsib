<x-layouts.app :title="$user->name">
    <x-slot:header>
        <x-page-header :title="$user->name" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Pentadbiran'],
            ['label' => 'Pengguna', 'url' => route('admin.users.index')],
            ['label' => $user->name],
        ]">
            <x-slot:actions>
                @can('update', $user)
                    <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" novalidate
                          data-confirm-delete
                          data-confirm-title="Set semula kata laluan"
                          data-confirm-message="Pautan set semula kata laluan akan dihantar ke {{ $user->email }}."
                          onsubmit="return confirm('Hantar pautan set semula kata laluan?')">
                        @csrf
                        <x-button type="submit" variant="outline" icon="lock">Set semula kata laluan</x-button>
                    </form>

                    <x-button href="{{ route('admin.users.edit', $user) }}" color="primary" icon="pencil">Sunting</x-button>
                @endcan
            </x-slot:actions>
        </x-page-header>
    </x-slot:header>

    @if (session('status') === 'user-created')
        <x-alert type="success" class="mb-3">Pengguna telah dicipta.</x-alert>
    @endif

    @if (session('status') === 'user-updated')
        <x-alert type="success" class="mb-3">Pengguna telah dikemas kini.</x-alert>
    @endif

    @if (session('status') === 'user-password-reset-sent')
        <x-alert type="success" class="mb-3">Pautan set semula kata laluan telah dihantar ke {{ $user->email }}.</x-alert>
    @endif

    @if (session('status') === 'user-password-reset-failed')
        <x-alert type="danger" class="mb-3">Gagal menghantar pautan set semula kata laluan. Sila cuba sebentar lagi.</x-alert>
    @endif

    <div class="row row-cards">
        <div class="col-md-4">
            <x-card>
                <div class="text-center">
                    <x-avatar
                        size="xl"
                        :src="$user->getFirstMediaUrl('avatar')"
                        :initials="Str::of($user->name)->substr(0, 1)->upper()"
                        :label="$user->name"
                        class="mb-3"
                    />
                    <h3 class="mb-1">{{ $user->name }}</h3>
                    <div class="text-secondary">{{ $user->email }}</div>
                </div>
            </x-card>
        </div>

        <div class="col-md-8">
            <x-card title="Butiran">
                <dl class="row mb-0">
                    <dt class="col-4 text-secondary">Peranan</dt>
                    <dd class="col-8">
                        @forelse ($user->roles as $role)
                            <x-badge color="purple" variant="light">{{ $role->name }}</x-badge>
                        @empty
                            <span class="text-secondary">Tiada peranan ditetapkan</span>
                        @endforelse
                    </dd>

                    <dt class="col-4 text-secondary mt-3">Tarikh sertai</dt>
                    <dd class="col-8 mt-3">{{ $user->created_at?->isoFormat('D MMMM YYYY, HH:mm') }}</dd>

                    <dt class="col-4 text-secondary mt-3">Terakhir dikemas kini</dt>
                    <dd class="col-8 mt-3">{{ $user->updated_at?->diffForHumans() }}</dd>
                </dl>
            </x-card>
        </div>
    </div>
</x-layouts.app>
