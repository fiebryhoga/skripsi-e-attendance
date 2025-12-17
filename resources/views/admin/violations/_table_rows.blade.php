<div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold">Siswa</th>
                    <th class="px-6 py-4 font-semibold">Jenis Pelanggaran</th>
                    <th class="px-6 py-4 font-semibold">Pelapor</th>
                    <th class="px-6 py-4 font-semibold text-right">Tanggal</th>

                    <th class="px-6 py-4 font-semibold text-center">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($violations as $data)
                    <tr onclick="window.location='{{ route('admin.student-violations.show', $data->id) }}'" 
                        class="group hover:bg-indigo-50/50 cursor-pointer transition-all duration-200 ease-in-out">
                        
                        {{-- Kolom 1: Siswa --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm ring-4 ring-white shadow-sm group-hover:ring-indigo-100 transition-all">
                                    {{ substr($data->student->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">
                                        {{ $data->student->name }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium border border-gray-200">
                                            {{ $data->student->nis ?? '-' }}
                                        </span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500 font-medium">
                                            {{ $data->student->classroom->name ?? 'Tanpa Kelas' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom 2: Pelanggaran (POIN DIHAPUS DISINI) --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-800">
                                    {{ $data->category->kode }}
                                </span>
                                <span class="text-xs text-gray-500 line-clamp-1 mt-0.5 max-w-[200px]" title="{{ $data->category->deskripsi }}">
                                    {{ $data->category->deskripsi }}
                                </span>
                            </div>
                        </td>

                        {{-- Kolom 3: Pelapor --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <span class="text-sm text-gray-600 font-medium">
                                    {{ $data->reporter->name ?? 'System' }}
                                </span>
                            </div>
                        </td>

                        {{-- Kolom 4: Tanggal --}}
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-medium text-gray-700">
                                {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $data->created_at->diffForHumans() }}
                            </div>
                        </td>

                        {{-- Kolom 5: Indikator Arrow --}}
                        <td class="px-6 py-4 text-center">
                            <div class="opacity-0 group-hover:opacity-100 transform translate-x-[-10px] group-hover:translate-x-0 transition-all duration-300">
                                <svg class="w-5 h-5 text-indigo-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center bg-gray-50">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                [Image of empty state folder icon]
                                <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-base font-medium text-gray-500">Belum ada data pelanggaran</p>
                                <p class="text-sm text-gray-400 mt-1">Klik tombol di atas untuk mencatat baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($violations->hasPages())
        <div class="bg-white px-6 py-4 border-t border-gray-100">
            {{ $violations->links() }}
        </div>
    @endif
</div>