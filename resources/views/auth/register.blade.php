<x-layouts.guest title="Sign up">
    <form class="card card-md" method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="card-body">
            <h2 class="card-title text-center mb-4">Create new account</h2>

            <x-input name="name" label="Name" placeholder="Enter name" autocomplete="name" autofocus required />

            <x-input type="email" name="email" label="Email address" placeholder="Enter email" autocomplete="email" required />

            <x-input type="password" name="password" label="Password" placeholder="Password" autocomplete="new-password" required />

            <x-input type="password" name="password_confirmation" label="Confirm password" placeholder="Confirm password" autocomplete="new-password" required />

            <div class="form-footer">
                <x-button type="submit" color="primary" icon="user-plus" block>Create new account</x-button>
            </div>
        </div>
    </form>

    <div class="text-center text-secondary mt-3">Already have an account? <a href="{{ route('login') }}">Sign in</a></div>
</x-layouts.guest>
