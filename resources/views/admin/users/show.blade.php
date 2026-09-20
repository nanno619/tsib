<x-layouts.app :title="$user->name">
    <x-slot:header>
        <x-page-header :title="$user->name" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Admin'],
            ['label' => 'Users', 'url' => route('admin.users.index')],
            ['label' => $user->name],
        ]">
            <x-slot:actions>
                @can('update', $user)
                    <x-button href="{{ route('admin.users.edit', $user) }}" color="primary" icon="pencil">Edit</x-button>
                @endcan
            </x-slot:actions>
        </x-page-header>
    </x-slot:header>

    @if (session('status') === 'user-created')
        <x-alert type="success" class="mb-3">The user has been created.</x-alert>
    @endif

    @if (session('status') === 'user-updated')
        <x-alert type="success" class="mb-3">The user has been updated.</x-alert>
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
            <x-card title="Details">
                <dl class="row mb-0">
                    <dt class="col-4 text-secondary">Roles</dt>
                    <dd class="col-8">
                        @forelse ($user->roles as $role)
                            <x-badge color="purple" variant="light">{{ $role->name }}</x-badge>
                        @empty
                            <span class="text-secondary">No roles assigned</span>
                        @endforelse
                    </dd>

                    <dt class="col-4 text-secondary mt-3">Joined</dt>
                    <dd class="col-8 mt-3">{{ $user->created_at?->isoFormat('D MMMM YYYY, HH:mm') }}</dd>

                    <dt class="col-4 text-secondary mt-3">Last updated</dt>
                    <dd class="col-8 mt-3">{{ $user->updated_at?->diffForHumans() }}</dd>
                </dl>
            </x-card>
        </div>
    </div>
</x-layouts.app>
