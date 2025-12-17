<x-app-layout>
    <x-slot name="header">Pengaturan Akun</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-xl shadow-indigo-100/50 border border-gray-100 overflow-hidden sticky top-24">
                        
                        <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="h-32 bg-gradient-to-r from-indigo-600 to-violet-600 relative overflow-hidden">
                                <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
                                <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
                            </div>

                            <div class="px-8 pb-8 text-center relative" x-data="avatarPreview()">
                                
                                <div class="relative inline-block -mt-16 mb-4 group">
                                    <div class="p-1.5 bg-white rounded-full relative">
                                        <img :src="src" 
                                             class="w-32 h-32 rounded-full object-cover border-4 border-indigo-50 shadow-md" 
                                             alt="{{ $user->name }}">
                                        
                                        <div class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white font-medium text-xs">
                                            <svg class="w-8 h-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </div>

                                        <input type="file" name="avatar" @change="previewFile" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" title="Ganti Foto">
                                    </div>

                                    <div class="absolute bottom-2 right-2 w-8 h-8 bg-indigo-600 text-white rounded-full border-4 border-white flex items-center justify-center shadow-sm z-10 pointer-events-none">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                </div>

                                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 mb-4">
                                    <span class="text-xs font-bold text-indigo-700 uppercase tracking-wide">
                                        {{ $user->role ?? 'Staf Admin' }}
                                    </span>
                                </div>

                                <div x-show="hasNewFile" style="display: none;" x-transition class="mt-2">
                                    <button type="submit" class="w-full py-2 bg-gray-900 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-black transition-all">
                                        Simpan Foto Baru
                                    </button>
                                </div>

                                @if (session('status') === 'avatar-updated')
                                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mt-3 text-sm text-green-600 font-bold bg-green-50 py-2 rounded-lg">
                                        Foto berhasil diperbarui! ✨
                                    </div>
                                @endif
                                @error('avatar')
                                    <div class="mt-3 text-sm text-red-600 bg-red-50 py-2 rounded-lg font-medium">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="mt-6 pt-6 border-t border-gray-50 flex flex-col gap-3 text-sm text-gray-500 text-left">
                                    <div class="flex items-center justify-between">
                                        <span>Bergabung</span>
                                        <span class="font-medium text-gray-700">{{ $user->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>Email</span>
                                        <span class="font-medium text-gray-700 truncate max-w-[150px]">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">

                    <div class="bg-white p-8 rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Informasi Profil</h3>
                                <p class="text-sm text-gray-500">Perbarui nama tampilan dan alamat email Anda.</p>
                            </div>
                        </div>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 px-4 font-medium transition-all">
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 px-4 font-medium transition-all">
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div class="flex items-center gap-4 pt-2">
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                                    Simpan Perubahan
                                </button>
                                
                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Tersimpan.
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="p-3 bg-orange-50 rounded-xl text-orange-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Update Password</h3>
                                <p class="text-sm text-gray-500">Pastikan akun Anda aman dengan password yang kuat.</p>
                            </div>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                            @csrf
                            @method('put')

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Saat Ini</label>
                                <input type="password" name="current_password" autocomplete="current-password"
                                    class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm py-3 px-4 transition-all">
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Baru</label>
                                    <input type="password" name="password" autocomplete="new-password"
                                        class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm py-3 px-4 transition-all">
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" autocomplete="new-password"
                                        class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm py-3 px-4 transition-all">
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-2">
                                <button type="submit" class="px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-xl shadow-lg shadow-gray-800/20 transition-all transform hover:-translate-y-0.5">
                                    Update Password
                                </button>
                                
                                @if (session('status') === 'password-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Tersimpan.
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="bg-red-50/50 p-8 rounded-3xl border border-red-100">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-red-100 rounded-xl text-red-600 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-red-700">Hapus Akun</h3>
                                <p class="text-sm text-red-600/80 mt-1 leading-relaxed">
                                    Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.
                                </p>
                                
                                <div class="mt-6">
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" 
                                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-red-500/30 transition-all">
                                        Hapus Akun Saya
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                            @csrf
                            @method('delete')

                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Apakah Anda yakin ingin menghapus akun?') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Setelah akun dihapus, semua data akan hilang permanen. Masukkan password Anda untuk konfirmasi.') }}
                            </p>
                            <div class="mt-6">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                                <input type="password" name="password" class="w-full rounded-xl border-gray-200 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 text-sm py-3 px-4" placeholder="Password Anda">
                                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                            </div>
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all">Hapus Akun</button>
                            </div>
                        </form>
                    </x-modal>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('avatarPreview', () => ({
                // Ambil URL default dari PHP
                src: "{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=6366f1&color=ffffff&size=128' }}",
                hasNewFile: false,

                previewFile(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.src = e.target.result;
                            this.hasNewFile = true;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }))
        })
    </script>
</x-app-layout>