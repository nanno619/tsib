<x-layouts.guest title="Sign in">
    <div class="card card-md">
        <div class="card-body">
            <h1 class="h2 text-center mb-4">Login to your account</h1>

            @if (session('status'))
                <x-alert type="success">{{ session('status') }}</x-alert>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <x-input type="email" name="email" label="Email address" placeholder="your@email.com" autocomplete="email" autofocus required />

                <x-input type="password" name="password" label="Password" placeholder="Your password" autocomplete="current-password" required>
                    <x-slot:labelAddon>
                        <a href="{{ route('password.request') }}">I forgot password</a>
                    </x-slot:labelAddon>
                </x-input>

                <div class="mb-2">
                    <x-checkbox name="remember">Remember me on this device</x-checkbox>
                </div>

                <div class="form-footer">
                    <x-button type="submit" color="primary" icon="login" block>Sign in</x-button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center text-secondary mt-3">Don't have account yet? <a href="{{ route('register') }}">Sign up</a></div>
</x-layouts.guest>
