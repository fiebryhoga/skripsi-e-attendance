<x-app-layout>
    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navigasi Kembali --}}
            <div class="mb-6">
                <a href="{{ route('admin.student-violations.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 relative">
                
                {{-- Decorative Line --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/30">
                    <h2 class="text-xl font-bold text-gray-800">
                        Catat Pelanggaran Baru
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Isi formulir berikut untuk merekam kedisiplinan siswa.</p>
                </div>

                <div class="p-8">
                    {{-- Form dengan Alpine.js untuk Preview Gambar --}}
                    <form action="{{ route('admin.student-violations.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="space-y-8"
                          x-data="{ 
                              photoPreview: null,
                              fileName: null,
                              previewFile(event) {
                                  const file = event.target.files[0];
                                  if (file) {
                                      this.fileName = file.name;
                                      const reader = new FileReader();
                                      reader.onload = (e) => { this.photoPreview = e.target.result; };
                                      reader.readAsDataURL(file);
                                  }
                              }
                          }">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Tanggal --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tanggal Kejadian</label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                                       class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 transition-all cursor-pointer text-gray-700 font-medium">
                                @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Siswa --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Siswa</label>
                                <select name="student_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 transition-all cursor-pointer">
                                    <option value="">-- Cari Nama Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} — ({{ $student->classroom->name ?? 'No Class' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jenis Pelanggaran</label>
                                <select name="violation_category_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 transition-all cursor-pointer">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $grup => $items)
                                        <optgroup label="Grup {{ $grup }}">
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" {{ old('violation_category_id') == $item->id ? 'selected' : '' }}>
                                                    [{{ $item->kode }}] {{ $item->deskripsi }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('violation_category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Kronologi --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kronologi / Catatan</label>
                                <textarea name="catatan" rows="4" 
                                          class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-gray-300"
                                          placeholder="Deskripsikan kronologi kejadian secara detail...">{{ old('catatan') }}</textarea>
                                @error('catatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Upload Bukti dengan Preview --}}
                        <div class="border-t border-gray-100 pt-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Bukti Foto (Opsional)</label>
                            
                            <div class="flex flex-col md:flex-row gap-6">
                                
                                {{-- Area Preview (Muncul jika ada foto) --}}
                                <div x-show="photoPreview" class="relative group w-full md:w-48 h-48 bg-gray-100 rounded-2xl overflow-hidden border-2 border-gray-200 shadow-sm flex-shrink-0" style="display: none;">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                    <button type="button" @click="photoPreview = null; fileName = null; $refs.fileInput.value = ''" 
                                            class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                {{-- Area Input Upload --}}
                                <div class="flex-1" x-show="!photoPreview">
                                    <div class="flex justify-center px-6 pt-10 pb-10 border-2 border-gray-300 border-dashed rounded-2xl hover:border-indigo-400 hover:bg-indigo-50/30 transition-all group cursor-pointer relative bg-gray-50/50">
                                        <div class="space-y-2 text-center">
                                            <div class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors bg-white rounded-full flex items-center justify-center shadow-sm">
                                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                <label for="file-upload" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                    <span>Upload foto bukti</span>
                                                    <input id="file-upload" x-ref="fileInput" name="bukti_foto" type="file" class="sr-only" @change="previewFile">
                                                </label>
                                                <p class="pl-1 inline">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB</p>
                                        </div>
                                        {{-- Overlay full click --}}
                                        <label for="file-upload" class="absolute inset-0 cursor-pointer"></label>
                                    </div>
                                </div>

                                {{-- Info Filename saat preview aktif --}}
                                <div x-show="photoPreview" class="flex-1 flex items-center text-sm text-gray-500 italic" style="display: none;">
                                    <span class="mr-2">File terpilih:</span>
                                    <span class="font-semibold text-gray-800" x-text="fileName"></span>
                                </div>
                            </div>
                            @error('bukti_foto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.student-violations.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>