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
                         src="{{ $student->photo ? Storage::url($student->photo) : 'https://ui-avatars.com/api/?name='.$student->name }}" alt="">
                    
                    <h2 class="mt-4 text-xl font-bold text-gray-800">{{ $student->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $student->nis }}</p>

                        <div class="mt-4 flex justify-center gap-2 flex-wrap px-4">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100">
                                X-RPL-1
                            </span>
                            
                            <span class="px-3 py-1 bg-orange-50 text-orange-700 rounded-full text-xs font-bold border border-orange-100">
                                Angkatan {{ $student->angkatan }}
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
                    </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-bold uppercase">Poin Pelanggaran</p>
                    <h3 class="text-2xl font-bold text-red-500 mt-1">0</h3>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-bold uppercase">Kehadiran (Bulan Ini)</p>
                    <h3 class="text-2xl font-bold text-green-500 mt-1">100%</h3>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-bold uppercase">Predikat</p>
                    <h3 class="text-2xl font-bold text-indigo-500 mt-1">Baik</h3>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Riwayat Pelanggaran Terakhir</h3>
                </div>
                <div class="p-6 text-center text-gray-400 py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p>Siswa ini belum memiliki catatan pelanggaran.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>