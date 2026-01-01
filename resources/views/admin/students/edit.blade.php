<x-app-layout>
    <x-slot name="header">Edit Data Siswa</x-slot>

    <div class="bg-gray-50/50 min-h-screen">
        <div class="space-y-8">

            
            <div class="mb-6">
                <a href="{{ route('admin.students.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Daftar Siswa
                </a>
            </div>

            <form action="{{ route('admin.students.update', $student) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    
                    <div class="lg:col-span-1 space-y-6">
                        
                        
                        <div class="bg-white p-6 rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 text-center">
                            <h3 class="font-bold text-gray-800 mb-4">Foto Profil</h3>
                            
                            <div class="relative w-40 h-40 mx-auto mb-4 group">
                                <div id="imagePreview" class="w-full h-full rounded-2xl bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300 relative">
                                    @if($student->photo)
                                        <img src="{{ Storage::url($student->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    @endif
                                </div>
                                
                                <label for="photo" class="absolute inset-0 bg-black/50 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white font-medium text-sm">
                                    Ganti Foto
                                </label>
                                <input type="file" name="photo" id="photo" class="hidden" onchange="previewFile()">
                            </div>
                            <p class="text-xs text-gray-400">Biarkan kosong jika tidak ingin mengubah foto.</p>
                        </div>

                        
                        <div class="bg-white p-6 rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100">
                            <h3 class="font-bold text-gray-800 mb-4">Penempatan Kelas</h3>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Kelas</label>
                                <select name="classroom_id" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 transition-all">
                                    <option value="">-- Belum ada Kelas --</option>
                                    @foreach($classrooms as $class)
                                        <option value="{{ $class->id }}" {{ old('classroom_id', $student->classroom_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} 
                                            @if($class->teacher) 
                                                (Wali: {{ Str::limit($class->teacher->name, 10) }}) 
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    
                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <span class="w-1 h-6 bg-indigo-500 rounded-full inline-block"></span>
                                Informasi Pribadi
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">NIS <span class="text-red-500">*</span></label>
                                    <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" required placeholder="Contoh: 12345" 
                                        class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 px-4 font-medium transition-all">
                                    @error('nis') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-1">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">NISN</label>
                                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}" placeholder="Nomor Induk Siswa Nasional" 
                                        class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 px-4 transition-all">
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required placeholder="Nama Siswa Sesuai Ijazah" 
                                        class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 px-4 font-bold text-gray-700 transition-all">
                                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="col-span-1">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Angkatan (Tahun Masuk) <span class="text-red-500">*</span></label>
                                    <select name="angkatan" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 transition-all">
                                        <option value="">Pilih Tahun</option>
                                        @for ($year = date('Y'); $year >= 2020; $year--)
                                            <option value="{{ $year }}" {{ old('angkatan', $student->angkatan) == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                                    <div class="flex gap-4">
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" name="gender" value="L" class="peer hidden" {{ old('gender', $student->gender) == 'L' ? 'checked' : '' }}>
                                            <div class="p-3 rounded-xl border border-gray-200 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition-all">
                                                Laki-laki
                                            </div>
                                        </label>
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" name="gender" value="P" class="peer hidden" {{ old('gender', $student->gender) == 'P' ? 'checked' : '' }}>
                                            <div class="p-3 rounded-xl border border-gray-200 text-center peer-checked:border-pink-500 peer-checked:bg-pink-50 peer-checked:text-pink-700 transition-all">
                                                Perempuan
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Agama</label>
                                    <select name="religion" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 transition-all">
                                        <option value="">Pilih Agama</option>
                                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $religion)
                                            <option value="{{ $religion }}" {{ old('religion', $student->religion) == $religion ? 'selected' : '' }}>
                                                {{ $religion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor HP Orang Tua</label>
                                    <div class="relative">
                                        
                                        <input type="text" name="phone_parent" value="{{ old('phone_parent', $student->phone_parent) }}" placeholder="0812xxxxx" 
                                            class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 pl-4 pr-4 transition-all">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-3">
                                <a href="{{ route('admin.students.index') }}" class="w-full md:w-auto px-6 py-3 rounded-xl bg-gray-100 text-gray-600 font-semibold hover:bg-gray-200 transition-colors text-center">Batal</a>
                                <button type="submit" class="w-full md:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transform hover:-translate-y-1 transition-all">
                                    Perbarui Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewFile() {
            const preview = document.querySelector('#imagePreview');
            const file = document.querySelector('input[type=file]').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                preview.innerHTML = `<img src="${reader.result}" class="w-full h-full object-cover">`;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>