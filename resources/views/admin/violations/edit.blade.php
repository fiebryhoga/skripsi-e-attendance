<x-app-layout>
    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('admin.student-violations.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/30 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        Sunting Laporan Pelanggaran
                    </h2>
                    <span class="text-xs font-mono text-gray-400 bg-white border border-gray-200 px-2 py-1 rounded">ID: {{ $violation->id }}</span>
                </div>

                <div class="p-8">
                    {{-- Form menggunakan x-data untuk preview image --}}
                    <form action="{{ route('admin.student-violations.update', $violation->id) }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="space-y-8"
                          x-data="{ 
                              photoPreview: '{{ $violation->bukti_foto ? Storage::url($violation->bukti_foto) : '' }}',
                              previewFile(event) {
                                  const file = event.target.files[0];
                                  if (file) {
                                      const reader = new FileReader();
                                      reader.onload = (e) => { this.photoPreview = e.target.result; };
                                      reader.readAsDataURL(file);
                                  }
                              }
                          }">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Tanggal --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tanggal Kejadian</label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', $violation->tanggal) }}" 
                                       class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 transition-all cursor-pointer">
                            </div>

                            {{-- Siswa --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Siswa</label>
                                <select name="student_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 transition-all cursor-pointer">
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id', $violation->student_id) == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} — {{ $student->classroom->name ?? 'No Class' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jenis Pelanggaran</label>
                            <select name="violation_category_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 transition-all cursor-pointer">
                                @foreach($categories as $grup => $items)
                                    <optgroup label="Grup {{ $grup }}">
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" {{ old('violation_category_id', $violation->violation_category_id) == $item->id ? 'selected' : '' }}>
                                                [{{ $item->kode }}] {{ $item->deskripsi }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kronologi --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan / Kronologi</label>
                            <textarea name="catatan" rows="4" 
                                      class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-gray-300"
                                      placeholder="Deskripsikan kronologi kejadian secara detail...">{{ old('catatan', $violation->catatan) }}</textarea>
                        </div>

                        <div class="border-t border-gray-100 pt-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Bukti Foto</label>
                            
                            <div class="flex items-start gap-6">
                                {{-- Preview Image Area --}}
                                <div x-show="photoPreview" class="relative group w-40 h-40 bg-gray-100 rounded-xl overflow-hidden border-2 border-gray-200 shadow-sm flex-shrink-0">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-white text-xs font-bold">Preview</span>
                                    </div>
                                </div>

                                {{-- Upload Area --}}
                                <div class="flex-1">
                                    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-indigo-400 hover:bg-indigo-50/30 transition-all group cursor-pointer relative">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="file-upload" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                    <span>Upload file baru</span>
                                                    <input id="file-upload" name="bukti_foto" type="file" class="sr-only" @change="previewFile">
                                                </label>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB</p>
                                        </div>
                                        {{-- Overlay full click --}}
                                        <label for="file-upload" class="absolute inset-0 cursor-pointer"></label>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2 italic">* Biarkan kosong jika tidak ingin mengubah foto bukti.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.student-violations.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>