<x-app-layout>
    <x-slot name="header">Kategori Pelanggaran</x-slot>

    <div class="">
        <div class=" space-y-6">
            
            {{-- Header Tanpa Tombol Tambah --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Master Kategori Pelanggaran
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Daftar referensi jenis pelanggaran tata tertib sekolah.
                    </p>
                </div>
                {{-- Tombol Tambah DIHAPUS --}}
            </div>

            {{-- Content: Looping per Grup (A, B, C) --}}
            @forelse($groupedViolations as $group => $violations)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    
                    {{-- Header Kartu per Grup --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-sm ring-4 ring-white">
                                {{ $group }}
                            </span>
                            <h3 class="font-semibold text-gray-800">
                                @if($group == 'A') Pelanggaran Ringan
                                @elseif($group == 'B') Pelanggaran Sedang
                                @elseif($group == 'C') Pelanggaran Berat
                                @else Grup Lainnya @endif
                            </h3>
                        </div>
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                            {{ count($violations) }} Item
                        </span>
                    </div>

                    {{-- Tabel --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 w-24">Kode</th>
                                    <th scope="col" class="px-6 py-3">Deskripsi Pelanggaran</th>
                                    {{-- Kolom Poin DIHAPUS --}}
                                    {{-- Kolom Aksi DIHAPUS --}}
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($violations as $item)
                                    <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                                        <td class="px-6 py-4 font-bold text-indigo-600 align-top">
                                            {{ $item->kode }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 align-top leading-relaxed">
                                            {{ $item->deskripsi }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-12 text-center">
                    <p class="text-gray-500">Belum ada data kategori pelanggaran.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>