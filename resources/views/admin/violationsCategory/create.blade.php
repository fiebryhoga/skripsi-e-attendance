<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kategori Pelanggaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- PERBAIKAN: Menggunakan route 'admin.violations.store' sesuai error trace --}}
                    <form action="{{ route('admin.violations.store') }}" method="POST">
                        @csrf
        
                        <div class="mb-4">
                            <label for="grup" class="block font-medium text-sm text-gray-700">Grup Pelanggaran</label>
                            <select name="grup" id="grup" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1 @error('grup') border-red-500 @enderror">
                                <option value="">-- Pilih Grup --</option>
                                <option value="A">Grup A (Pelanggaran Ringan)</option>
                                <option value="B">Grup B (Pelanggaran Sedang)</option>
                                <option value="C">Grup C (Pelanggaran Berat)</option>
                                <option value="D">Grup D (Sangat Berat)</option>
                            </select>
                            @error('grup')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
        
                        <div class="mb-4">
                            <label for="kode" class="block font-medium text-sm text-gray-700">Kode Pelanggaran</label>
                            <input type="text" name="kode" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1 @error('kode') border-red-500 @enderror" value="{{ old('kode') }}" placeholder="Contoh: A01">
                            @error('kode')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
        
                        <div class="mb-4">
                            <label for="deskripsi" class="block font-medium text-sm text-gray-700">Deskripsi</label>
                            <textarea name="deskripsi" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1 @error('deskripsi') border-red-500 @enderror" rows="3">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="poin" class="block font-medium text-sm text-gray-700">Poin</label>
                            <input type="number" name="poin" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" value="{{ old('poin') }}">
                        </div>
        
                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan
                            </button>
                            <a href="{{ route('admin.violations.index') }}" class="text-gray-600 hover:text-gray-900">Kembali</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>