<x-filament::page>
    {{-- Arsip Surat Masuk --}}
    <x-filament::card>
        <h2 class="text-xl font-bold mb-4">Arsip Surat Masuk</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 font-medium text-gray-700">Nomor Surat</th>
                        <th class="px-4 py-2 font-medium text-gray-700">Perihal</th>
                        <th class="px-4 py-2 font-medium text-gray-700">Sifat</th>
                        <th class="px-4 py-2 font-medium text-gray-700">Tanggal Arsip</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($incoming as $mail)
                        <tr>
                            <td class="px-4 py-2">{{ $mail->mail_number }}</td>
                            <td class="px-4 py-2">{{ $mail->subject }}</td>
                            <td class="px-4 py-2">
                                <x-filament::badge color="primary">{{ ucfirst($mail->priority) }}</x-filament::badge>
                            </td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($mail->created_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">Tidak ada arsip surat masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::card>

    {{-- Arsip Surat Keluar --}}
    <x-filament::card class="mt-6">
        <h2 class="text-xl font-bold mb-4">Arsip Surat Keluar</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 font-medium text-gray-700">Nomor Surat</th>
                        <th class="px-4 py-2 font-medium text-gray-700">Perihal</th>
                        <th class="px-4 py-2 font-medium text-gray-700">Sifat</th>
                        <th class="px-4 py-2 font-medium text-gray-700">Tanggal Arsip</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($outgoing as $mail)
                        <tr>
                            <td class="px-4 py-2">{{ $mail->mail_number }}</td>
                            <td class="px-4 py-2">{{ $mail->subject }}</td>
                            <td class="px-4 py-2">
                                <x-filament::badge color="success">{{ ucfirst($mail->priority) }}</x-filament::badge>
                            </td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($mail->created_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">Tidak ada arsip surat keluar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::card>
</x-filament::page>
