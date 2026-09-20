<x-layouts.app title="Password">
    <x-slot:header>
        <x-page-header title="Password" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Settings'],
            ['label' => 'Password'],
        ]" />
    </x-slot:header>

    <div class="card">
        <div class="row g-0">
            <x-settings.nav />

            <div class="col-12 col-md-9 d-flex flex-column">
                <form method="POST" action="{{ route('user-password.update') }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <h2 class="card-title">Update password</h2>
                        <p class="card-subtitle">Use a long, random password to keep your account secure.</p>

                        @if (session('status') === 'password-updated')
                            <x-alert type="success">Your password has been updated.</x-alert>
                        @endif

                        <x-input type="password" name="current_password" label="Current password" autocomplete="current-password" error-bag="updatePassword" required />

                        <x-input type="password" name="password" label="New password" autocomplete="new-password" error-bag="updatePassword" required />

                        <x-input type="password" name="password_confirmation" label="Confirm new password" autocomplete="new-password" required />
                    </div>
                    <div class="card-footer bg-transparent mt-auto">
                        <div class="btn-list justify-content-end">
                            <x-button type="submit" color="primary" icon="lock">Update password</x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
