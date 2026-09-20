<div class="col-12 col-md-3 border-end">
    <div class="card-body">
        <h4 class="subheader">Account settings</h4>
        <x-list-group as="nav" aria-label="Account settings" transparent>
            <x-list-group-item href="{{ route('settings.profile') }}" class="d-flex align-items-center" :active="request()->routeIs('settings.profile')">Profile</x-list-group-item>
            <x-list-group-item href="{{ route('settings.password') }}" class="d-flex align-items-center" :active="request()->routeIs('settings.password')">Password</x-list-group-item>
            <x-list-group-item href="{{ route('settings.activity') }}" class="d-flex align-items-center" :active="request()->routeIs('settings.activity')">Activity</x-list-group-item>
        </x-list-group>
    </div>
</div>
