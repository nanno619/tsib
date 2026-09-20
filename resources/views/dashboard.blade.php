<x-layouts.app title="Dashboard">
    <x-slot:header>
        <x-page-header title="Dashboard" />
    </x-slot:header>

    <div class="row row-deck row-cards">
        <div class="col-12">
            <x-card>
                <h3 class="h2">Welcome back, {{ auth()->user()->name }}</h3>
                <div class="text-secondary">This is your starting point &mdash; wire this page up to your project's real metrics.</div>
            </x-card>
        </div>

        <div class="col-sm-6 col-lg-3">
            <x-card>
                <div class="subheader">Total Users</div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">75,782</div>
                    <div class="me-auto">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            <span class="visually-hidden">Increased by </span>2%
                        </span>
                    </div>
                </div>
                <div class="text-secondary mt-2">24,635 users increased from last month</div>
            </x-card>
        </div>

        <div class="col-sm-6 col-lg-3">
            <x-card>
                <div class="subheader">Active Users</div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">25,782</div>
                    <div class="me-auto">
                        <span class="text-red d-inline-flex align-items-center lh-1">
                            <span class="visually-hidden">Decreased by </span>-1%
                        </span>
                    </div>
                </div>
                <div class="text-secondary mt-2">1,463 users decreased from last month</div>
            </x-card>
        </div>

        <div class="col-sm-6 col-lg-3">
            <x-card>
                <div class="subheader">Revenue</div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">$4,300</div>
                    <div class="me-auto">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            <span class="visually-hidden">Increased by </span>5%
                        </span>
                    </div>
                </div>
                <div class="text-secondary mt-2">$430 increased from last month</div>
            </x-card>
        </div>

        <div class="col-sm-6 col-lg-3">
            <x-card>
                <div class="subheader">New Clients</div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2">6,782</div>
                    <div class="me-auto">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            <span class="visually-hidden">Increased by </span>3%
                        </span>
                    </div>
                </div>
                <div class="text-secondary mt-2">203 clients increased from last month</div>
            </x-card>
        </div>

        <div class="col-12">
            <x-card title="Getting started">
                <p class="mb-0">This starter kit ships the Tabler UI, authentication, and a settings area already wired up. Check the <a href="{{ route('starter-kit') }}">Starter Kit</a> page for the available components.</p>
            </x-card>
        </div>
    </div>
</x-layouts.app>
