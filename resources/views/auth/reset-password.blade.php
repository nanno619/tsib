<x-layouts.guest title="Reset password">
    <form class="card card-md" method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}" />

        <div class="card-body">
            <h2 class="card-title text-center mb-4">Reset password</h2>

            <x-input type="email" name="email" label="Email address" :value="$request->email" autocomplete="email" required />

            <x-input type="password" name="password" label="New password" autocomplete="new-password" required />

            <x-input type="password" name="password_confirmation" label="Confirm new password" autocomplete="new-password" required />

            <div class="form-footer">
                <x-button type="submit" color="primary" icon="lock" block>Reset password</x-button>
            </div>
        </div>
    </form>
</x-layouts.guest>
