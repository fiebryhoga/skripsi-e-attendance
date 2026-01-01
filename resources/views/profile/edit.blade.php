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
                                             class="w-32 h-32 rounded-full object-cover border-4 border-indigo-50 shadow-md transition-transform duration-300 group-hover:scale-105" 
                                             alt="{{ $user->name }}">
                                        
                                        
                                        <div class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white font-medium text-xs backdrop-blur-[2px]">
                                            <svg class="w-8 h-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </div>

                                        
                                        <input type="file" name="avatar" @change="previewFile" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" title="Ganti Foto">
                                    </div>

                                    
                                    <div class="absolute bottom-2 right-2 w-8 h-8 bg-indigo-600 text-white rounded-full border-4 border-white flex items-center justify-center shadow-sm z-10 pointer-events-none">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                </div>

                                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                                
                                
                                <div class="mt-3 flex justify-center gap-2 flex-wrap">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-bold text-indigo-700 uppercase tracking-wide">
                                            {{ $role->label() ?? $role->value }}
                                        </span>
                                    @endforeach
                                    @if($user->roles->isEmpty())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-50 border border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wide">
                                            Staff Admin
                                        </span>
                                    @endif
                                </div>

                                
                                <div x-show="hasNewFile" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                                    <button type="submit" class="w-full py-2.5 text-white text-sm font-bold rounded-xl shadow-lg bg-indigo-600 hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        Simpan Foto Baru
                                    </button>
                                </div>

                                @if (session('status') === 'avatar-updated')
                                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mt-3 text-sm text-green-600 font-bold bg-green-50 py-2 rounded-lg border border-green-100 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Foto berhasil diperbarui!
                                    </div>
                                @endif
                                @error('avatar')
                                    <div class="mt-3 text-sm text-red-600 bg-red-50 py-2 rounded-lg font-medium border border-red-100">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="mt-6 pt-6 border-t border-gray-50 flex flex-col gap-3 text-sm text-gray-500 text-left">
                                    <div class="flex items-center justify-between group">
                                        <span class="group-hover:text-indigo-600 transition-colors">Bergabung</span>
                                        <span class="font-medium text-gray-700 bg-gray-50 px-2 py-1 rounded">{{ $user->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between group">
                                        <span class="group-hover:text-indigo-600 transition-colors">Email</span>
                                        <span class="font-medium text-gray-700 bg-gray-50 px-2 py-1 rounded truncate max-w-[180px]" title="{{ $user->email }}">{{ $user->email }}</span>
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 px-4 font-medium transition-all shadow-sm">
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 px-4 font-medium transition-all shadow-sm">
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-2">
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Simpan Perubahan
                                </button>
                                
                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium flex items-center gap-1 bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Tersimpan.
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    
                    <div class="bg-white p-8 rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 relative overflow-hidden">
                        
                        <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-bl-full -mr-10 -mt-10 z-0"></div>

                        <div class="flex items-center gap-4 mb-6 relative z-10">
                            <div class="p-3 bg-orange-50 rounded-xl text-orange-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Update Password</h3>
                                <p class="text-sm text-gray-500">Pastikan akun Anda aman dengan password yang kuat.</p>
                            </div>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-5 relative z-10">
                            @csrf
                            @method('put')

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Saat Ini</label>
                                <div class="relative">
                                    <input type="password" name="current_password" autocomplete="current-password"
                                        class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm py-3 px-4 transition-all shadow-sm pl-10" placeholder="••••••••">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Baru</label>
                                    <div class="relative">
                                        <input type="password" name="password" autocomplete="new-password"
                                            class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm py-3 px-4 transition-all shadow-sm pl-10" placeholder="••••••••">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                                    <div class="relative">
                                        <input type="password" name="password_confirmation" autocomplete="new-password"
                                            class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-sm py-3 px-4 transition-all shadow-sm pl-10" placeholder="••••••••">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-2">
                                <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-lg shadow-gray-800/20 transition-all transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-gray-800">
                                    Update Password
                                </button>
                                
                                @if (session('status') === 'password-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium flex items-center gap-1 bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Tersimpan.
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    
                    
                    <div class="bg-red-50/50 p-8 rounded-3xl border border-red-100" 
                         x-data="{ showDeleteModal: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
                        
                        
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
                                    <button @click="showDeleteModal = true" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-red-500/30 transition-all transform hover:-translate-y-0.5">
                                        Hapus Akun Saya
                                    </button>
                                </div>
                            </div>
                        </div>

                        
                        <div x-show="showDeleteModal" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            
                            
                            <div x-show="showDeleteModal" 
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
                      
                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                    
                                    
                                    <div x-show="showDeleteModal" 
                                         @click.away="showDeleteModal = false"
                                         x-transition:enter="ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave="ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                                        
                                        <form method="post" action="{{ route('profile.destroy') }}">
                                            @csrf
                                            @method('delete')

                                            
                                            <div class="bg-red-50 px-4 py-3 sm:px-6 flex items-center justify-between border-b border-red-100">
                                                <h3 class="text-base font-bold leading-6 text-red-700 flex items-center gap-2" id="modal-title">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                                    Konfirmasi Hapus Akun
                                                </h3>
                                                <button type="button" @click="showDeleteModal = false" class="text-red-400 hover:text-red-600 transition">
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                </button>
                                            </div>

                                            
                                            <div class="px-4 py-5 sm:p-6">
                                                <p class="text-sm text-gray-500 mb-4">
                                                    Apakah Anda yakin ingin menghapus akun ini secara permanen? Semua data yang terkait akan hilang selamanya.
                                                </p>
                                                
                                                <div>
                                                    <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Anda</label>
                                                    <input type="password" name="password" id="password" class="w-full rounded-xl border-gray-200 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 text-sm py-3 px-4 shadow-sm" placeholder="Masukkan password untuk konfirmasi">
                                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                                </div>
                                            </div>

                                            
                                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3 border-t border-gray-100">
                                                <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-500/30 hover:bg-red-700 sm:ml-3 sm:w-auto transition-all transform hover:-translate-y-0.5">
                                                    Ya, Hapus Akun
                                                </button>
                                                <button type="button" @click="showDeleteModal = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    
                    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <form method="post" action="{{ route('profile.destroy') }}" class="p-0">
                            @csrf
                            @method('delete')

                            
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900">
                                    {{ __('Konfirmasi Hapus Akun') }}
                                </h2>
                            </div>

                            
                            <div class="p-6">
                                <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                    {{ __('Apakah Anda yakin ingin menghapus akun ini secara permanen? Semua data yang terkait akan hilang dan tidak dapat dikembalikan. Silakan masukkan password Anda untuk mengonfirmasi.') }}
                                </p>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Anda</label>
                                    <input type="password" name="password" class="w-full rounded-xl border-gray-200 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 text-sm py-3 px-4 shadow-sm" placeholder="Masukkan password untuk konfirmasi">
                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                </div>
                            </div>

                            
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-lg">
                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-colors shadow-sm">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Ya, Hapus Akun
                                </button>
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
                // Menggunakan avatar user atau inisial jika tidak ada
                src: "{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=6366f1&color=ffffff&size=128' }}",
                hasNewFile: false,

                previewFile(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.src = e.target.result;
                            this.hasNewFile = true; // Munculkan tombol simpan
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }))
        })
    </script>
</x-app-layout>