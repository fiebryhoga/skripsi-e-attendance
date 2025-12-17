<x-app-layout>
    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navigasi Kembali --}}
            <div class="mb-6">
                <a href="{{ route('admin.student-violations.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-3 hover:border-indigo-200 hover:bg-indigo-50 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white shadow-xl sm:rounded-3xl border border-gray-100 overflow-hidden relative">
                
                {{-- Decorative Header Line --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500"></div>

                <div class="p-8 sm:p-10">
                    
                    {{-- Header Laporan --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-10 border-b border-gray-100 pb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-bold tracking-widest uppercase border border-red-100">
                                    Laporan Pelanggaran
                                </span>
                                <span class="text-xs text-gray-400 font-medium">#ID-{{ $violation->id }}</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                                Detail Kasus Siswa
                            </h1>
                            <p class="text-gray-500 mt-2 flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Tanggal Kejadian: <span class="font-semibold text-gray-700 ml-1">{{ \Carbon\Carbon::parse($violation->tanggal)->translatedFormat('l, d F Y') }}</span>
                            </p>
                        </div>
                        
                        {{-- Status timestamp --}}
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-gray-400">Dibuat pada</p>
                            <p class="text-sm font-medium text-gray-600">{{ $violation->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        {{-- KOLOM KIRI: Detail Siswa & Pelapor --}}
                        <div class="md:col-span-1 space-y-6">
                            
                            {{-- Card Siswa --}}
                            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Pelaku Pelanggaran</h3>
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="relative">
                                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg border-2 border-white shadow-sm">
                                            {{ substr($violation->student->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm leading-tight">{{ $violation->student->name }}</div>
                                        <div class="text-xs text-indigo-600 font-medium mt-0.5">{{ $violation->student->classroom->name ?? 'Tanpa Kelas' }}</div>
                                    </div>
                                </div>
                                <div class="pt-3 border-t border-gray-200/50 flex justify-between text-xs">
                                    <span class="text-gray-500">Nomor Induk (NIS)</span>
                                    <span class="font-mono font-bold text-gray-700">{{ $violation->student->nis ?? '-' }}</span>
                                </div>
                            </div>

                            {{-- Info Pelapor --}}
                            <div class="px-2">
                                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Dilaporkan Oleh</h3>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $violation->reporter->name ?? 'System Administrator' }}</span>
                                </div>
                            </div>

                        </div>

                        {{-- KOLOM KANAN: Detail Kejadian --}}
                        <div class="md:col-span-2 space-y-8">
                            
                            {{-- Jenis Pelanggaran --}}
                            <div>
                                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Jenis Pelanggaran</h3>
                                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                                    <span class="inline-block px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded mb-2">{{ $violation->category->kode }}</span>
                                    <p class="text-lg font-medium text-gray-800 leading-snug">
                                        {{ $violation->category->deskripsi }}
                                    </p>
                                </div>
                            </div>

                            {{-- Kronologi --}}
                            <div>
                                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Kronologi / Catatan Tambahan</h3>
                                <div class="bg-amber-50 rounded-xl p-5 border border-amber-100 text-amber-900 leading-relaxed text-sm">
                                    @if($violation->catatan)
                                        {{ $violation->catatan }}
                                    @else
                                        <span class="italic text-amber-900/50">Tidak ada catatan kronologi yang ditambahkan.</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Bukti Foto --}}
                            @if($violation->bukti_foto)
                                <div>
                                    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Bukti Foto Kejadian</h3>
                                    <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-md group relative">
                                        <img src="{{ Storage::url($violation->bukti_foto) }}" class="w-full h-auto object-cover max-h-80 hover:scale-105 transition-transform duration-500" alt="Bukti Pelanggaran">
                                        
                                        <a href="{{ Storage::url($violation->bukti_foto) }}" target="_blank" class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <span class="px-4 py-2 bg-white/90 backdrop-blur rounded-lg text-xs font-bold text-gray-800 shadow-lg">Lihat Ukuran Penuh</span>
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- Action Footer --}}
                    <div class="mt-10 pt-8 border-t border-gray-100 flex justify-end items-center gap-3">
                        <a href="{{ route('admin.student-violations.edit', $violation->id) }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-50 hover:text-indigo-600 transition shadow-sm">
                            Edit Data
                        </a>
                        
                        <form action="{{ route('admin.student-violations.destroy', $violation->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pelanggaran ini? Data yang dihapus tidak dapat dikembalikan.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 transition shadow-lg shadow-red-500/20 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Hapus Laporan
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>