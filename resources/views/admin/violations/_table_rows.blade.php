<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Pelanggaran</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Bukti</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pelapor</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                @forelse($violations as $violation)
                    <tr class="hover:bg-gray-50/80 transition-colors duration-200 group">
                        
                        {{-- Tanggal --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-700">
                                {{ \Carbon\Carbon::parse($violation->tanggal)->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ $violation->created_at->format('H:i') }} WIB
                            </div>
                        </td>

                        {{-- Siswa --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                    {{ substr($violation->student->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 line-clamp-1">{{ $violation->student->name }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">
                                        {{ $violation->student->classroom->name ?? 'Tanpa Kelas' }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Detail Pelanggaran (Deskripsi, Kode, Grup) --}}
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                {{-- Nama Pelanggaran (Deskripsi) --}}
                                <div class="text-sm font-medium text-gray-800 mb-1">
                                    {{ $violation->category->deskripsi }}
                                </div>

                                {{-- Badges Kode & Grup --}}
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    <span class="px-1.5 py-0.5 rounded border border-gray-200 text-[10px] font-mono text-gray-500 bg-white">
                                        {{ $violation->category->kode }}
                                    </span>
                                    
                                    @php
                                        // Styling otomatis berdasarkan nama grup
                                        $grup = strtoupper($violation->category->grup);
                                        $badgeClass = match(true) {
                                            str_contains($grup, 'BERAT') => 'bg-red-50 text-red-700 border-red-100',
                                            str_contains($grup, 'SEDANG') => 'bg-orange-50 text-orange-700 border-orange-100',
                                            str_contains($grup, 'RINGAN') => 'bg-blue-50 text-blue-700 border-blue-100',
                                            default => 'bg-gray-50 text-gray-600 border-gray-100'
                                        };
                                    @endphp
                                    <span class="px-1.5 py-0.5 rounded border text-[10px] font-bold tracking-wide {{ $badgeClass }}">
                                        {{ $violation->category->grup }}
                                    </span>
                                </div>

                                {{-- Catatan (Quote Style) --}}
                                @if($violation->catatan)
                                    <div class="relative pl-3 text-xs italic text-gray-500 border-l-2 border-indigo-200">
                                        "{{ Str::limit($violation->catatan, 50) }}"
                                    </div>
                                @endif
                            </div>
                        </td>

                        {{-- Bukti Foto --}}
                        <td class="px-6 py-4 text-center align-middle">
                            @if($violation->bukti_foto)
                                <button onclick="openPhotoModal('{{ Storage::url($violation->bukti_foto) }}', '{{ addslashes($violation->student->name) }}')" 
                                        class="group/img relative inline-block w-12 h-12 rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-all">
                                    <img src="{{ Storage::url($violation->bukti_foto) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover/img:scale-110" alt="Bukti">
                                    
                                    {{-- Overlay Icon Zoom --}}
                                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </button>
                            @else
                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-gray-50 border border-gray-100 border-dashed text-gray-300">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </span>
                            @endif
                        </td>

                        {{-- Pelapor --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <span class="text-xs font-medium text-gray-600">
                                    {{ $violation->reporter->name ?? 'System' }}
                                </span>
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.student-violations.edit', $violation->id) }}" 
                                   class="text-gray-400 hover:text-indigo-600 transition-colors p-2 rounded-full hover:bg-indigo-50" 
                                   title="Edit Data">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.student-violations.destroy', $violation->id) }}" method="POST" onsubmit="return confirm('Hapus data pelanggaran ini? Data tidak bisa dikembalikan.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-2 rounded-full hover:bg-red-50" title="Hapus Data">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Belum ada data pelanggaran</h3>
                                <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">
                                    Gunakan tombol "Catat Pelanggaran" di atas untuk menambahkan data baru.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($violations->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $violations->links() }}
        </div>
    @endif
</div>