@php
    $record = $this->record ?? null;
@endphp

<span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Preview File</span>

@if ($record?->file_path)
    @php
        $path = $record->file_path;
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $url = asset('storage/' . $path);
    @endphp

    @if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
        <img src="{{ $url }}" alt="Preview File" class="mt-2 max-w-full rounded-lg shadow" />
    @elseif ($ext === 'pdf')
        <iframe src="{{ $url }}" width="100%" height="600px" class="rounded-lg shadow mt-2"></iframe>
    @else
        <a href="{{ $url }}" target="_blank" class="text-sm text-blue-500 underline mt-2 inline-block">
            Download File
        </a>
    @endif
@else
    <p class="text-gray-500 mt-2">No file uploaded.</p>
@endif
