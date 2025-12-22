<x-app-layout>
    <x-slot name="header">Kategori Pelanggaran</x-slot>

    <div class="">
        <div class="space-y-6">
            
            {{-- Header + Tombol Tambah --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Master Kategori Pelanggaran
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Daftar referensi jenis pelanggaran tata tertib sekolah.
                    </p>
                </div>
                
                <a href="{{ route('admin.violations.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/30">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Kategori
                </a>
            </div>

            {{-- Alert Sukses --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

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
                                    <th scope="col" class="px-6 py-3 w-32 text-center">Aksi</th>
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
                                        <td class="px-6 py-4 align-top text-center">
                                            <div class="flex justify-center gap-2">
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('admin.violations.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>

                                                {{-- Tombol Delete --}}
                                                <form action="{{ route('admin.violations.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-12 text-center">
                    <div class="mb-4">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada data</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan kategori pelanggaran baru.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.violations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Data
                        </a>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>