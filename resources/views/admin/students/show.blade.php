<x-app-layout>
    <x-slot name="header">Detail Siswa</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.students.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 overflow-hidden border border-gray-100 relative">
                <div class="h-32 bg-gradient-to-r from-indigo-500 to-violet-600"></div>
                
                <div class="px-6 pb-6 text-center -mt-16">
                    <img class="w-32 h-32 rounded-2xl object-cover mx-auto border-4 border-white shadow-md bg-white" 
                         src="{{ $student->photo ? Storage::url($student->photo) : 'https://ui-avatars.com/api/?name='.$student->name.'&background=E0E7FF&color=4F46E5' }}" alt="">
                    
                    <h2 class="mt-4 text-xl font-bold text-gray-800">{{ $student->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $student->nis }}</p>

                    <div class="mt-4 flex justify-center gap-2 flex-wrap px-4">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100">
                            {{ $student->classroom->name ?? 'Tanpa Kelas' }}
                        </span>
                        <span class="px-3 py-1 {{ $student->gender == 'L' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-pink-50 text-pink-700 border-pink-100' }} rounded-full text-xs font-bold border">
                            {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </div>

                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <a href="{{ route('admin.students.edit', $student) }}" class="block w-full py-2.5 bg-gray-800 text-white rounded-xl font-medium hover:bg-gray-900 transition shadow-lg shadow-gray-800/20">Edit Profil</a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 p-6 mt-6 border border-gray-100">
                <h4 class="font-bold text-gray-800 mb-4">Informasi Kontak</h4>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Telepon Ortu</p>
                            <p class="font-medium text-gray-700">{{ $student->phone_parent ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">NISN</p>
                            <p class="font-medium text-gray-700">{{ $student->nisn ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2 space-y-6">
            
            
            <div class="grid grid-cols-2 gap-4">
                
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Pelanggaran</p>
                        <h3 class="text-3xl font-black text-gray-800 mt-1">
                            {{ $student->violations->count() }} 
                            <span class="text-sm font-medium text-gray-400">Kasus</span>
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>

                
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Kehadiran</p>
                        <h3 class="text-3xl font-black text-green-500 mt-1">100%</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/30 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Riwayat Pelanggaran</h3>
                    @if($student->violations->count() > 0)
                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-lg">{{ $student->violations->count() }} Data</span>
                    @endif
                </div>

                @if($student->violations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-3 font-semibold">Tanggal</th>
                                    <th class="px-6 py-3 font-semibold">Jenis Pelanggaran</th>
                                    
                                    <th class="px-6 py-3 font-semibold">Pelapor</th>
                                    <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($student->violations as $violation)
                                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                                        
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-bold text-gray-700">
                                                {{ \Carbon\Carbon::parse($violation->tanggal)->format('d/m/Y') }}
                                            </span>
                                            <div class="text-[10px] text-gray-400">
                                                {{ $violation->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        
                                        
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $violation->category->kode }}
                                            </div>
                                            <div class="text-xs text-gray-500 line-clamp-1" title="{{ $violation->category->deskripsi }}">
                                                {{ $violation->category->deskripsi }}
                                            </div>
                                        </td>

                                        

                                        
                                        <td class="px-6 py-4 text-xs text-gray-600">
                                            {{ $violation->reporter->name ?? 'System' }}
                                        </td>

                                        
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('admin.student-violations.show', $violation->id) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition shadow-sm"
                                               title="Lihat Detail Laporan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    
                    <div class="p-6 text-center text-gray-400 py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm font-medium text-gray-500">Siswa ini bersih dari catatan pelanggaran.</p>
                        <p class="text-xs text-gray-400 mt-1">Pertahankan kinerja baik ini!</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>