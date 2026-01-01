<x-app-layout>
    <x-slot name="header">Detail Profil Guru</x-slot>

    
    <div class="mb-6">
        <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 transition-colors font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" 
          x-data="teacherEditForm()">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden sticky top-24">
                     
                     <div class="h-32 bg-gray-900 relative overflow-hidden">
                        <div class="absolute inset-0 opacity-30 bg-[radial-gradient(#ffffff33_1px,transparent_1px)] [background-size:16px_16px]"></div>
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/30 rounded-full blur-3xl"></div>
                    </div>

                    <div class="px-8 pb-8 text-center relative -mt-16">
                        
                        <div class="relative inline-block group">
                            <div class="relative w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white cursor-pointer"
                                 @click="triggerFileInput">
                                <img :src="photoPreview" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                
                                <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <svg class="w-8 h-8 text-white mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    <span class="text-[10px] font-bold text-white uppercase tracking-wider">Ubah Foto</span>
                                </div>
                            </div>

                            <input type="file" x-ref="fileInput" name="avatar" class="hidden" accept="image/*" @change="updatePreview">

                            @if($teacher->roles->contains(\App\Enums\UserRole::ADMIN))
                                <div class="absolute bottom-1 right-1 bg-yellow-400 text-white p-1.5 rounded-full border-2 border-white shadow-md z-10 pointer-events-none">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            @endif
                        </div>

                        <h2 class="text-2xl font-bold text-gray-800 mt-4 leading-tight">{{ $teacher->name }}</h2>
                        <div class="mt-2">
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 font-mono text-sm rounded-lg font-medium tracking-wide">{{ $teacher->nip }}</span>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100">
                             <p class="text-xs text-gray-400 mb-2">Terdaftar sejak {{ $teacher->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 p-8">
                    
                    
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Informasi Akun</h3>
                            <p class="text-sm text-gray-500">Edit biodata dan hak akses pengguna.</p>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>

                    <div class="space-y-6">
                        
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required 
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 font-bold text-gray-800 text-lg transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">NIP</label>
                                <input type="text" name="nip" value="{{ old('nip', $teacher->nip) }}" required 
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 font-mono transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $teacher->email) }}" 
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 transition-all">
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nomor Telepon / WhatsApp <span class="text-gray-400 text-xs">(Opsional)</span>
                                </label>
                                
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    
                                    <input type="text" 
                                        name="phone" 
                                        id="phone" 
                                        value="{{ old('phone', $teacher->phone ?? '') }}" 
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Contoh: 08123456789 atau +628123456789">
                                </div>
                                
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="border-t border-gray-100 my-4"></div>

                        
                        <div id="roles-container">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Penugasan Jabatan</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach(\App\Enums\UserRole::cases() as $role)
                                    <label class="relative flex items-start p-4 rounded-xl border-2 cursor-pointer transition-all hover:bg-gray-50 
                                                  {{ $teacher->hasRole($role) ? 'border-indigo-600 bg-indigo-50/20' : 'border-gray-100' }}">
                                        <div class="flex items-center h-5">
                                            
                                            <input type="checkbox" name="roles[]" value="{{ $role->value }}" 
                                                   class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                   {{ $teacher->hasRole($role) ? 'checked' : '' }}
                                                   @change="checkRoles($el)">
                                        </div>
                                        <div class="ml-3">
                                            <span class="font-bold text-gray-800 block text-sm">{{ $role->label() }}</span>
                                            <span class="text-xs text-gray-500 mt-0.5 block">
                                                {{ $role === \App\Enums\UserRole::ADMIN ? 'Akses penuh ke semua fitur sistem.' : 'Akses terbatas sesuai tugas.' }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-gray-100 my-4"></div>
                        
                        
                        <div x-data="{ showPass: false }" class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold text-gray-700 text-sm">Keamanan Akun</h4>
                                    <p class="text-xs text-gray-400">Kosongkan jika tidak ingin mengubah password.</p>
                                </div>
                                <button type="button" @click="showPass = !showPass" class="text-xs font-bold text-indigo-600 hover:underline">
                                    <span x-text="showPass ? 'Batal Ubah' : 'Ubah Password'"></span>
                                </button>
                            </div>
                            <div x-show="showPass" x-transition class="mt-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Baru</label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter..."
                                       class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 bg-white transition-all">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('teacherEditForm', () => ({
                photoPreview: "{{ $teacher->avatar ? Storage::url($teacher->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=1f2937&color=ffffff' }}",
                
                triggerFileInput() {
                    this.$refs.fileInput.click();
                },

                updatePreview(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.photoPreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                // FUNGSI BARU: Auto-check semua jika Admin dipilih
                checkRoles(element) {
                    // Ambil nilai enum untuk ADMIN dari Blade
                    const adminValue = "{{ \App\Enums\UserRole::ADMIN->value }}";
                    
                    // Jika yang diklik adalah Admin dan dicentang
                    if (element.value === adminValue && element.checked) {
                        // Cari semua checkbox dengan nama roles[] di dalam form
                        const allCheckboxes = document.querySelectorAll('input[name="roles[]"]');
                        allCheckboxes.forEach(checkbox => {
                            checkbox.checked = true;
                        });
                    }
                }
            }));
        });
    </script>
</x-app-layout>