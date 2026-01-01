<x-app-layout>
    <div class="min-h-screen bg-gray-50/50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Kategori</h2>
                    <p class="text-sm text-gray-500 mt-2">Perbarui informasi pelanggaran.</p>
                </div>
                <a href="{{ route('admin.violations.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-orange-400 via-red-500 to-pink-500"></div>

                <div class="p-8 md:p-10">
                    <form action="{{ route('admin.violations.update', $violationCategory->id) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-600 text-xs font-extrabold">01</span>
                                Klasifikasi
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Grup Pelanggaran</label>
                                    <div class="relative">
                                        <select name="grup" class="block w-full border-gray-200 rounded-xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 text-gray-800 font-medium py-3 shadow-sm transition-all appearance-none cursor-pointer">
                                            <option value="A" {{ old('grup', $violationCategory->grup) == 'A' ? 'selected' : '' }}>Grup A (Ringan)</option>
                                            <option value="B" {{ old('grup', $violationCategory->grup) == 'B' ? 'selected' : '' }}>Grup B (Sedang)</option>
                                            <option value="C" {{ old('grup', $violationCategory->grup) == 'C' ? 'selected' : '' }}>Grup C (Berat)</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </div>
                                    </div>
                                    @error('grup')
                                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kode Pelanggaran</label>
                                    <input type="text" name="kode" value="{{ old('kode', $violationCategory->kode) }}" 
                                           class="block w-full border-gray-200 rounded-xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 text-gray-800 font-bold py-3 shadow-sm transition-all">
                                    @error('kode')
                                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-50">

                        
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 text-xs font-extrabold">02</span>
                                Detail Informasi
                            </h3>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Pelanggaran</label>
                                    <textarea name="deskripsi" rows="3" 
                                              class="block w-full border-gray-200 rounded-xl focus:ring-4 focus:ring-red-500/10 focus:border-red-500 text-gray-800 py-3 shadow-sm transition-all">{{ old('deskripsi', $violationCategory->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        
                        <div class="pt-6 border-t border-gray-50 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.violations.index') }}" class="px-6 py-3 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white font-bold rounded-xl hover:from-orange-600 hover:to-red-700 shadow-lg shadow-orange-500/30 hover:shadow-orange-500/50 transition-all transform hover:-translate-y-0.5">
                                Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>