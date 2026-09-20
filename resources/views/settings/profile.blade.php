<x-layouts.app title="Profile">
    <x-slot:header>
        <x-page-header title="Profile" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Settings'],
            ['label' => 'Profile'],
        ]" />
    </x-slot:header>

    <div class="card">
        <div class="row g-0">
            <x-settings.nav />

            <div class="col-12 col-md-9 d-flex flex-column">
                <div class="card-body border-bottom">
                    <h2 class="card-title">Avatar</h2>

                    @if (session('status') === 'avatar-updated')
                        <x-alert type="success">Your avatar has been updated.</x-alert>
                    @endif

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <x-avatar size="xl" :src="auth()->user()->getFirstMediaUrl('avatar')" :initials="Str::of(auth()->user()->name)->substr(0, 1)->upper()" />
                        <div class="text-secondary" id="avatar-hint">JPG, PNG or WEBP. Max 2MB.</div>
                    </div>

                    <form method="POST" action="{{ route('settings.avatar.update') }}" enctype="multipart/form-data" class="d-flex align-items-start gap-2" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="flex-fill">
                            {{-- Deliberately no visible label: the card-title above
                                 already reads "Avatar", and a second one directly
                                 over the input reads as two labels for one field.
                                 aria-label names the control, and aria-describedby
                                 ties the format and size limits to it — without
                                 that, a screen reader user only learns them from a
                                 failed upload. No `required` either: it's inert
                                 under novalidate and there's no label to mark, so
                                 it would only imply a rule that never fires. --}}
                            <x-input type="file" name="avatar" accept="image/*" error-bag="avatar"
                                     aria-label="Avatar image" aria-describedby="avatar-hint" />
                        </div>

                        <x-button type="submit" color="primary" icon="upload">Upload</x-button>
                    </form>
                </div>

                <form method="POST" action="{{ route('user-profile-information.update') }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <h2 class="card-title">Profile details</h2>

                        @if (session('status') === 'profile-information-updated')
                            <x-alert type="success">Your profile has been updated.</x-alert>
                        @endif

                        <x-input name="name" label="Name" :value="auth()->user()->name" error-bag="updateProfileInformation" required />

                        <x-input type="email" name="email" label="Email address" :value="auth()->user()->email" error-bag="updateProfileInformation" required />
                    </div>
                    <div class="card-footer bg-transparent mt-auto">
                        <div class="btn-list justify-content-end">
                            <x-button type="submit" color="primary" icon="device-floppy">Save changes</x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
