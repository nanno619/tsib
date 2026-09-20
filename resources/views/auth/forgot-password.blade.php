<x-layouts.guest title="Forgot password">
    <form class="card card-md" method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="card-body">
            <h2 class="card-title text-center mb-4">Forgot password</h2>

            <p class="text-secondary mb-4">Enter your email address and we'll send you a link to reset your password.</p>

            @if (session('status'))
                <x-alert type="success">{{ session('status') }}</x-alert>
            @endif

            <x-input type="email" name="email" label="Email address" placeholder="Enter email" autocomplete="email" autofocus required />

            <div class="form-footer">
                <x-button type="submit" color="primary" icon="mail" block>Send password reset link</x-button>
            </div>
        </div>
    </form>

    <div class="text-center text-secondary mt-3">Forget it, <a href="{{ route('login') }}">send me back</a> to the sign in screen.</div>
</x-layouts.guest>
