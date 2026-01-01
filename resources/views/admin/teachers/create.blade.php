<x-app-layout>
    <x-slot name="header">Tambah Pengajar Baru</x-slot>

    
    <div class="mb-6">
        <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 transition-colors font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-8 rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <span class="p-2 bg-indigo-50 text-indigo-600 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg></span>
                            Identitas & Akun
                        </h3>
                        <p class="text-sm text-gray-500 ml-11">Lengkapi data pribadi untuk login.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso, S.Pd"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 font-bold text-gray-700 placeholder-gray-300 transition-all">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">NIP / ID Pegawai <span class="text-red-500">*</span></label>
                            <input type="text" name="nip" value="{{ old('nip') }}" required placeholder="198xxxxxxx"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 font-mono transition-all">
                            @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email (Opsional)</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@sekolah.sch.id"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor WhatsApp (WA)</label>
                            <div class="relative">
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0812xxxxx"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 pl-4 pr-4 transition-all">
                            </div>
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Awal <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required placeholder="Minimal 8 karakter"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 transition-all">
                        </div>
                    </div>
                </div>

                
                <div class="bg-white p-8 rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100">
                     <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <span class="p-2 bg-pink-50 text-pink-600 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></span>
                            Foto Profil
                        </h3>
                    </div>
                    <input type="file" name="avatar" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer">
                    <p class="text-xs text-gray-400 mt-2">* Format: JPG, PNG, Max 2MB. Boleh dikosongkan.</p>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 h-full">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <span class="p-2 bg-orange-50 text-orange-600 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg></span>
                            Penugasan Role
                        </h3>
                        <p class="text-sm text-gray-500">Pilih satu atau lebih.</p>
                    </div>

                    
                    <div class="space-y-3" x-data="{
                        handleRoleChange(e) {
                            if (e.target.value === '{{ \App\Enums\UserRole::ADMIN->value }}' && e.target.checked) {
                                // Centang semua checkbox lain di dalam form ini
                                document.querySelectorAll('input[name=\'roles[]\']').forEach(cb => cb.checked = true);
                            }
                        }
                    }">
                        @foreach(\App\Enums\UserRole::cases() as $role)
                            <label class="relative flex items-start p-4 rounded-xl border-2 cursor-pointer transition-all hover:bg-gray-50 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30 border-gray-100">
                                <div class="flex items-center h-5">
                                    
                                    <input type="checkbox" name="roles[]" value="{{ $role->value }}" 
                                           class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                           {{ in_array($role->value, old('roles', [])) ? 'checked' : '' }}
                                           @change="handleRoleChange">
                                </div>
                                <div class="ml-3">
                                    <span class="font-bold text-gray-800 block text-sm">{{ $role->label() }}</span>
                                    <span class="text-xs text-gray-500 block mt-0.5 leading-snug">
                                        @if($role == \App\Enums\UserRole::ADMIN)
                                            Akses penuh sistem, kelola user & data master.
                                        @elseif($role == \App\Enums\UserRole::WALI_KELAS)
                                            Kelola siswa, presensi & laporan kelas.
                                        @elseif($role == \App\Enums\UserRole::GURU_TATIB)
                                            Input pelanggaran & poin siswa.
                                        @else
                                            Guru pengajar biasa.
                                        @endif
                                    </span>
                                </div>
                            </label>
                        @endforeach
                        @error('roles') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <button type="submit" class="w-full py-3.5 bg-blue-800 hover:bg-blue-900 text-white font-bold rounded-xl shadow-lg shadow-gray-900/20 transition-all transform hover:-translate-y-1">
                            Simpan Data Guru
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</x-app-layout>