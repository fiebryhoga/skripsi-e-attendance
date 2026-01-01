<x-app-layout>
    
    <div class="py-6 md:py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            
            
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.student-violations.index') }}" 
                       class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition-all flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800 leading-tight">Detail Pelanggaran</h2>
                        <p class="text-xs md:text-sm text-gray-500">ID Referensi: #{{ $violation->id }}</p>
                    </div>
                </div>

                
                <div class="flex gap-3 w-full md:w-auto">
                    <a href="{{ route('admin.student-violations.edit', $violation->id) }}" class="flex-1 md:flex-none justify-center inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        Edit
                    </a>
                    
                    <form action="{{ route('admin.student-violations.destroy', $violation->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');" class="flex-1 md:flex-none">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg font-semibold text-xs text-red-600 uppercase tracking-widest hover:bg-red-100 hover:border-red-300 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                
                <div class="lg:col-span-2 space-y-6">
                    
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                Informasi Pelanggaran
                            </h3>
                            
                            
                            @php
                                $grup = strtoupper($violation->category->grup);
                                $badgeClass = match(true) {
                                    str_contains($grup, 'BERAT') => 'bg-red-100 text-red-800 border-red-200',
                                    str_contains($grup, 'SEDANG') => 'bg-orange-100 text-orange-800 border-orange-200',
                                    default => 'bg-blue-100 text-blue-800 border-blue-200'
                                };
                            @endphp
                            <span class="inline-block text-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                Kategori {{ $violation->category->grup }}
                            </span>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wide">Jenis Pelanggaran</label>
                                    <p class="mt-1 text-base font-semibold text-gray-800 leading-snug">{{ $violation->category->deskripsi }}</p>
                                    <p class="text-xs text-gray-500 font-mono mt-0.5">Kode: {{ $violation->category->kode }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wide">Waktu Kejadian</label>
                                    <p class="mt-1 text-base font-semibold text-gray-800">
                                        {{ \Carbon\Carbon::parse($violation->tanggal)->translatedFormat('l, d F Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">Dicatat pukul {{ $violation->created_at->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100">
                                <label class="text-xs font-bold text-indigo-400 uppercase tracking-wide mb-2 block">Catatan Tambahan</label>
                                <p class="text-gray-700 text-sm leading-relaxed italic">
                                    "{{ $violation->catatan ?? 'Tidak ada catatan khusus yang ditambahkan.' }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                Bukti Foto
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($violation->bukti_foto)
                                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                    <img src="{{ Storage::url($violation->bukti_foto) }}" alt="Bukti Pelanggaran" class="w-full h-auto object-cover max-h-[500px]">
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <a href="{{ Storage::url($violation->bukti_foto) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                                        Lihat Ukuran Penuh
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    </a>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="text-gray-500 font-medium">Tidak ada bukti foto dilampirkan.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-1 space-y-6">
                    
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                        
                        <div class="h-24 bg-gradient-to-br from-indigo-500 to-purple-600 absolute top-0 w-full z-0"></div>
                        
                        <div class="pt-12 px-6 pb-6 relative z-10 text-center">
                            <div class="w-20 h-20 mx-auto rounded-full bg-white border-4 border-white shadow-md flex items-center justify-center text-indigo-600 font-bold text-3xl mb-3">
                                {{ substr($violation->student->name, 0, 1) }}
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $violation->student->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $violation->student->nis ?? 'NIS Belum diisi' }}</p>
                            
                            <div class="mt-4 flex justify-center">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100">
                                    Kelas {{ $violation->student->classroom->name ?? '-' }}
                                </span>
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-100 text-left space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 font-medium">Wali Kelas</span>
                                    <span class="text-xs text-gray-800 font-bold">{{ Str::limit($violation->student->classroom->teacher->name ?? '-', 15) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 font-medium">Kontak Ortu</span>
                                    <span class="text-xs text-gray-800 font-bold">{{ $violation->student->phone_parent ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-4">Pelapor Data</h4>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex-shrink-0 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div class="min-w-0"> 
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $violation->reporter->name ?? 'Sistem' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $violation->reporter->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>