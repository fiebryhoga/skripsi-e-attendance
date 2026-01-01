<x-app-layout>
    
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        
        .ts-control {
            border-radius: 0.75rem; 
            padding: 0.75rem 1rem;
            border-color: #e5e7eb; 
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            font-size: 0.875rem; 
            background-color: #fff;
        }
        .ts-control:focus {
            border-color: #6366f1; 
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); 
        }
        .ts-dropdown {
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
            padding: 0.5rem;
            z-index: 9999; 
        }
        .ts-dropdown .option {
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
        }
        .ts-dropdown .active {
            background-color: #f5f3ff; 
            color: #4f46e5; 
        }
    </style>

    <div class="min-h-screen bg-gray-50/50 py-8 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Catat Pelanggaran</h2>
                    <p class="text-sm text-gray-500 mt-1 sm:mt-2">Cari siswa berdasarkan Nama, NIS, atau Kelas.</p>
                </div>
                <a href="{{ route('admin.student-violations.index') }}" 
                   class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali
                </a>
            </div>

            
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 relative">
                
                <div class="h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-t-3xl"></div>
                
                <div class="p-6 sm:p-10">
                    <form action="{{ route('admin.student-violations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold">01</span>
                                Data Utama
                            </h3>
                            
                            
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                
                                
                                <div class="sm:col-span-3">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tanggal</label>
                                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                                        class="block w-full border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm sm:text-sm h-[46px]" required>
                                </div>

                                
                                <div class="sm:col-span-6">
                                    <label for="student_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                        Cari Siswa (Nama / NIS / Kelas)
                                    </label>
                                    
                                    <select name="student_id" id="select-student" placeholder="Ketik Nama, NIS, atau Kelas..." autocomplete="off" required>
                                        <option value="">Ketik untuk mencari...</option>
                                        @foreach($classrooms as $classroom)
                                            @foreach($classroom->students as $student)
                                                <option value="{{ $student->id }}" 
                                                        data-nis="{{ $student->nis ?? '-' }}" 
                                                        data-class="{{ $classroom->name }}"
                                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    @error('student_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 text-xs font-bold">02</span>
                                Detail
                            </h3>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jenis Pelanggaran</label>
                                    <select name="violation_category_id" class="block w-full border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 shadow-sm sm:text-sm py-3 px-4" required>
                                        <option value="" disabled selected>Pilih jenis pelanggaran...</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('violation_category_id') == $cat->id ? 'selected' : '' }}>
                                                [{{ $cat->kode }}] {{ $cat->deskripsi }} ({{ $cat->grup }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan (Opsional)</label>
                                    <textarea name="catatan" rows="3" placeholder="Tambahkan keterangan tambahan jika ada..." class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm sm:text-sm">{{ old('catatan') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Bukti Foto</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-indigo-400 transition-colors bg-gray-50/50">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                    <span>Upload file</span>
                                                    <input id="file-upload" name="bukti_foto" type="file" accept="image/*" class="sr-only">
                                                </label>
                                                <p class="pl-1">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                        </div>
                                    </div>
                                    
                                    <input type="file" name="bukti_foto" accept="image/*" class="mt-4 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors sm:hidden">
                                </div>
                            </div>
                        </div>

                        
                        <div class="pt-6 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <a href="{{ route('admin.student-violations.index') }}" class="w-full sm:w-auto px-6 py-3 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 text-center transition-all">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 text-center transition-all">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect("#select-student", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            searchField: ['text', 'nis', 'class'], 
            plugins: ['clear_button'],
            render: {
                option: function(data, escape) {
                    return '<div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">' +
                        '<div>' +
                            '<span class="font-bold text-gray-800 block text-sm">' + escape(data.text) + '</span>' +
                            '<span class="text-xs text-gray-500">NIS: ' + escape(data.nis) + '</span>' +
                        '</div>' +
                        '<span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-1 rounded ml-2">' + escape(data.class) + '</span>' +
                    '</div>';
                },
                item: function(data, escape) {
                    return '<div>' + escape(data.text) + ' <span class="text-gray-400 text-xs ml-1">(' + escape(data.class) + ')</span></div>';
                }
            }
        });
    </script>
</x-app-layout>