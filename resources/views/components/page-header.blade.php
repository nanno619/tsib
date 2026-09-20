@props(['title', 'subtitle' => null, 'breadcrumbs' => []])

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                @if (! empty($breadcrumbs))
                    <x-breadcrumb :items="$breadcrumbs" class="mb-2" />
                @endif
                @if ($subtitle)
                    <div class="page-pretitle">{{ $subtitle }}</div>
                @endif
                <h2 class="page-title">{{ $title }}</h2>
            </div>
            @isset($actions)
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        {{ $actions }}
                    </div>
                </div>
            @endisset
        </div>
    </div>
</div>
