<x-layouts.app :title="ucfirst($role->name)">
    <x-slot:header>
        <x-page-header :title="ucfirst($role->name)" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Pentadbiran'],
            ['label' => 'Access Control', 'url' => route('admin.roles.index')],
            ['label' => ucfirst($role->name)],
        ]" />
    </x-slot:header>

    <div class="row row-cards">
        <div class="col-md-4">
            <x-card title="Kebenaran">
                @forelse ($permissions as $permission)
                    <x-badge color="purple" variant="light" class="mb-1">{{ $permission->name }}</x-badge>
                @empty
                    <span class="text-secondary">Tiada kebenaran ditetapkan</span>
                @endforelse
            </x-card>
        </div>

        <div class="col-md-8">
            <x-card title="Pengguna ({{ $role->users_count }})">
                @if ($users->isEmpty())
                    <x-empty icon="user" title="Tiada pengguna" subtitle="Belum ada pengguna dengan peranan ini." />
                @else
                    <x-table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>E-mel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a>
                                    </td>
                                    <td class="text-secondary">{{ $user->email }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-table>

                    <x-pagination :paginator="$users" class="m-0" />
                @endif
            </x-card>
        </div>
    </div>
</x-layouts.app>
