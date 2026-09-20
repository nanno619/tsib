{{--
    Access Control landing page — a read-only overview of every role and how
    many users hold it. Roles are fixed (see App\Http\Controllers\Admin\RoleController);
    there's no create/edit/delete here, only a "View" action to each role's
    detail page and a "Users" count that links into the filtered user list.
--}}
<x-layouts.app title="Access Control">
    <x-slot:header>
        <x-page-header title="Access Control" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Pentadbiran'],
            ['label' => 'Access Control'],
        ]" />
    </x-slot:header>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Peranan</h3>
        </div>

        @if ($roles->isEmpty())
            <div class="card-body">
                <x-empty icon="shield" title="Belum ada peranan" subtitle="Peranan akan dipaparkan di sini." />
            </div>
        @else
            <x-table card>
                <thead>
                    <tr>
                        <th class="w-1"><span class="visually-hidden">Tindakan</span></th>
                        <th>Nama</th>
                        <th>Pengguna</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>
                                <a href="{{ route('admin.roles.show', $role) }}" class="action-icon"
                                   aria-label="Lihat peranan {{ $role->name }}"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat">
                                    <x-icon name="eye" />
                                </a>
                            </td>
                            <td>
                                <x-badge color="purple" variant="light">{{ ucfirst($role->name) }}</x-badge>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.index', ['roles' => [$role->name]]) }}">
                                    {{ $role->users_count }} pengguna
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
        @endif
    </div>
</x-layouts.app>
