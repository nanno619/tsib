<x-layouts.app title="Activity">
    <x-slot:header>
        <x-page-header title="Activity" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Settings'],
            ['label' => 'Activity'],
        ]" />
    </x-slot:header>

    <div class="card">
        <div class="row g-0">
            <x-settings.nav />

            <div class="col-12 col-md-9 d-flex flex-column">
                <div class="card-body">
                    <h2 class="card-title">Recent activity</h2>
                    <p class="card-subtitle">Changes made to your account, most recent first.</p>

                    @if ($activities->isEmpty())
                        <x-empty icon="history" title="No activity yet" subtitle="Changes to your profile will show up here." />
                    @else
                        <x-list-group flush>
                            @foreach ($activities as $activity)
                                <x-list-group-item class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div>{{ $activity->description }}</div>
                                        {{-- v5 stores the diff on `attribute_changes` ({old, attributes}) — the
                                             v4 `properties`/`changes()` API no longer exists. --}}
                                        @if ($activity->attribute_changes?->get('attributes'))
                                            <div class="text-secondary small">
                                                @foreach ($activity->attribute_changes->get('attributes', []) as $field => $newValue)
                                                    {{ $field }}:
                                                    @if (array_key_exists($field, $activity->attribute_changes->get('old', [])))
                                                        <span class="text-decoration-line-through">{{ $activity->attribute_changes->get('old')[$field] }}</span>
                                                        <span class="text-secondary">&rarr;</span>
                                                    @endif
                                                    <span class="text-body">{{ $newValue }}</span>@if (! $loop->last), @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-secondary text-nowrap ms-3" title="{{ $activity->created_at }}">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </div>
                                </x-list-group-item>
                            @endforeach
                        </x-list-group>
                    @endif
                </div>

                @if ($activities->hasPages())
                    <div class="card-footer bg-transparent mt-auto d-flex align-items-center">
                        <p class="m-0 text-secondary">
                            Showing <span>{{ $activities->firstItem() }}</span> to <span>{{ $activities->lastItem() }}</span> of <span>{{ $activities->total() }}</span> entries
                        </p>
                        <x-pagination :paginator="$activities" class="m-0 ms-auto" />
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
