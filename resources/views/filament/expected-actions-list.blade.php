@php
    $actions = $this->record?->expected_actions ?? [];
@endphp

@if (is_array($actions) && count($actions))
    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Dengan Hormat Harap</span>

    <div class="flex flex-wrap gap-1">
        @foreach ($actions as $action)
            <span
                class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 shadow">
                {{ $action }}
            </span>
        @endforeach
    </div>
@else
    <p class="text-sm text-gray-500 italic">Tidak ada tindakan.</p>
@endif
