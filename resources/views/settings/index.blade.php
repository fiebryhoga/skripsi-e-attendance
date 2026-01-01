<x-app-layout>
    <x-slot name="header">Pengaturan & Maintenance</x-slot>

    
    <div class="py-12 bg-gray-50 min-h-screen" x-data="{ activeModal: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Zona Pengaturan Data</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola siklus data sekolah, mulai dari pergantian semester hingga reset sistem.</p>
            </div>

            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center justify-between text-green-800 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 p-2 rounded-full"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                        <div>
                            <span class="font-bold block">Berhasil!</span>
                            <span class="text-sm">{{ session('success') }}</span>
                        </div>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center justify-between text-red-800 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="bg-red-100 p-2 rounded-full"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                        <div>
                            <span class="font-bold block">Gagal!</span>
                            <span class="text-sm">{{ session('error') }}</span>
                        </div>
                    </div>
                    <button @click="show = false" class="text-red-500 hover:text-red-700">&times;</button>
                </div>
            @endif

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                    <div class="h-2 bg-yellow-400 w-full"></div>
                    <div class="p-8">
                        <div class="w-14 h-14 rounded-2xl bg-yellow-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Reset Semester</h3>
                        <p class="text-sm text-gray-500 leading-relaxed mb-6 h-20">
                            Hapus data <strong>Jadwal Pelajaran</strong> dan <strong>Riwayat Presensi</strong> untuk memulai semester baru. Data siswa dan kelas tidak akan hilang.
                        </p>
                        <button @click="activeModal = 'semester'" class="w-full py-3 rounded-xl bg-yellow-400 hover:bg-yellow-500 text-white font-bold shadow-lg shadow-yellow-400/30 transition-all transform hover:-translate-y-1">
                            Mulai Reset Semester
                        </button>
                    </div>
                </div>

                
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                    <div class="h-2 bg-orange-500 w-full"></div>
                    <div class="p-8">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Kenaikan Kelas</h3>
                        <p class="text-sm text-gray-500 leading-relaxed mb-6 h-20">
                            Siswa kelas XII lulus (dihapus). Kelas X & XI naik tingkat. Jadwal & Presensi dihapus. <br><span class="text-orange-600 text-xs font-bold">*Pastikan kelas tujuan sudah dibuat.</span>
                        </p>
                        <button @click="activeModal = 'academic_year'" class="w-full py-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold shadow-lg shadow-orange-500/30 transition-all transform hover:-translate-y-1">
                            Proses Kenaikan
                        </button>
                    </div>
                </div>

                
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group relative">
                    <div class="h-2 bg-red-600 w-full"></div>
                    <div class="p-8">
                        <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Factory Reset</h3>
                        <p class="text-sm text-gray-500 leading-relaxed mb-6 h-20">
                            <strong>DANGER ZONE.</strong> Menghapus SELURUH data aplikasi (Siswa, Guru, Kelas, Jadwal, dll). Mengembalikan aplikasi ke kondisi baru install.
                        </p>
                        <button @click="activeModal = 'all'" class="w-full py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold shadow-lg shadow-red-600/30 transition-all transform hover:-translate-y-1">
                            Hapus Semua Data
                        </button>
                    </div>
                </div>

            </div>
        </div>

        
        
        
        <div x-show="activeModal === 'semester'" style="display: none;" class="relative z-50">
            
            <div x-show="activeModal === 'semester'" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

            
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div x-show="activeModal === 'semester'" 
                     @click.away="activeModal = null"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                    
                    <div class="bg-yellow-50 p-6 border-b border-yellow-100 flex items-center gap-4">
                        <div class="bg-yellow-100 p-2 rounded-full text-yellow-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-yellow-800">Konfirmasi Reset Semester</h3>
                    </div>

                    <form method="POST" action="{{ route('admin.settings.reset') }}" class="p-6">
                        @csrf @method('DELETE')
                        <input type="hidden" name="type" value="semester">

                        <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                            Anda akan menghapus data <strong>Jadwal</strong> dan <strong>Presensi</strong>. Data siswa dan struktur kelas tetap aman. Silakan masukkan password admin untuk melanjutkan.
                        </p>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Password Admin</label>
                            <input type="password" name="password_confirmation" required class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 py-2.5 px-4 text-sm" placeholder="••••••••">
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="activeModal = null" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-white font-bold rounded-xl hover:bg-yellow-600 shadow-lg shadow-yellow-500/30 transition-all">Reset Semester</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        
        
        <div x-show="activeModal === 'academic_year'" style="display: none;" class="relative z-50">
            <div x-show="activeModal === 'academic_year'" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div x-show="activeModal === 'academic_year'" 
                     @click.away="activeModal = null"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                    
                    <div class="bg-orange-50 p-6 border-b border-orange-100 flex items-center gap-4">
                        <div class="bg-orange-100 p-2 rounded-full text-orange-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-orange-800">Konfirmasi Kenaikan Kelas</h3>
                    </div>

                    <form method="POST" action="{{ route('admin.settings.reset') }}" class="p-6">
                        @csrf @method('DELETE')
                        <input type="hidden" name="type" value="academic_year">

                        <div class="bg-orange-50 p-3 rounded-lg border border-orange-100 mb-4">
                            <ul class="text-xs text-orange-800 list-disc list-inside font-medium space-y-1">
                                <li>Kelas XII akan dihapus (Lulus).</li>
                                <li>Kelas X & XI akan naik tingkat.</li>
                                <li>Jadwal & Presensi akan dibersihkan.</li>
                            </ul>
                        </div>

                        <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                            Pastikan Anda sudah membuat kelas tujuan (misal XI-J, XII-J). Jika kelas tujuan tidak ada, siswa akan berstatus tanpa kelas.
                        </p>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Password Admin</label>
                            <input type="password" name="password_confirmation" required class="w-full rounded-xl border-gray-300 focus:border-orange-500 focus:ring-orange-500 py-2.5 px-4 text-sm" placeholder="••••••••">
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="activeModal = null" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 shadow-lg shadow-orange-500/30 transition-all">Proses Kenaikan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        
        
        <div x-show="activeModal === 'all'" style="display: none;" class="relative z-50">
            <div x-show="activeModal === 'all'" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div x-show="activeModal === 'all'" 
                     @click.away="activeModal = null"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                    
                    <div class="bg-red-50 p-6 border-b border-red-100 flex items-center gap-4">
                        <div class="bg-red-100 p-2 rounded-full text-red-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-red-800">Peringatan: Reset Total</h3>
                    </div>

                    <form method="POST" action="{{ route('admin.settings.reset') }}" class="p-6">
                        @csrf @method('DELETE')
                        <input type="hidden" name="type" value="all">

                        <div class="bg-red-50 p-3 rounded-lg border border-red-100 mb-4">
                            <ul class="text-xs text-red-800 list-disc list-inside font-medium space-y-1">
                                <li>Semua <strong>SISWA</strong> dihapus.</li>
                                <li>Semua <strong>GURU</strong> dihapus (Kecuali Anda).</li>
                                <li>Jadwal, Presensi, Pelanggaran dibersihkan.</li>
                                <li class="text-green-700 font-bold">Data Kelas & Kategori Pelanggaran TIDAK dihapus.</li>
                            </ul>
                        </div>

                        <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                            Masukkan password admin untuk melanjutkan proses pembersihan data ini.
                        </p>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Password Admin</label>
                            <input type="password" name="password_confirmation" required class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 py-2.5 px-4 text-sm" placeholder="••••••••">
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="activeModal = null" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-600/30 transition-all">Hapus Siswa & Guru</button>
                        </div>
                    </form>
                </div>
            </div>

    </div>
</x-app-layout>