<x-app-layout>
    {{-- Tambahkan CSS Tom Select --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Custom Styling agar Tom Select cocok dengan Tailwind/Desain Anda */
        .ts-control {
            border-radius: 0.75rem; /* rounded-xl */
            padding: 0.75rem 1rem;
            border-color: #e5e7eb; /* border-gray-200 */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            font-size: 0.875rem; /* text-sm */
        }
        .ts-control:focus {
            border-color: #6366f1; /* border-indigo-500 */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); /* ring-indigo */
        }
        .ts-dropdown {
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
            padding: 0.5rem;
            z-index: 50;
        }
        .ts-dropdown .option {
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
        }
        .ts-dropdown .active {
            background-color: #f5f3ff; /* bg-indigo-50 */
            color: #4f46e5; /* text-indigo-600 */
        }
    </style>

    <div class="min-h-screen bg-gray-50/50 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Catat Pelanggaran</h2>
                    <p class="text-sm text-gray-500 mt-2">Cari siswa berdasarkan Nama, NIS, atau Kelas.</p>
                </div>
                <a href="{{ route('admin.student-violations.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                <div class="p-8 md:p-10">
                    <form action="{{ route('admin.student-violations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        {{-- Section 1: Informasi --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 text-xs">01</span>
                                Data Utama
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Tanggal --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tanggal</label>
                                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                                        class="block w-full border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm sm:text-sm" required>
                                </div>

                                {{-- PENCARIAN SISWA (MODIFIED) --}}
                                <div class="md:col-span-2">
                                    <label for="student_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Cari Siswa (Nama / NIS / Kelas)</label>
                                    
                                    <select name="student_id" id="select-student" placeholder="Ketik Nama, NIS, atau Kelas..." autocomplete="off" required>
                                        <option value="">Ketik untuk mencari...</option>
                                        @foreach($classrooms as $classroom)
                                            {{-- Kita loop per siswa, tapi kita tambahkan data extra di atribut data- --}}
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

                        {{-- Section 2: Pelanggaran --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 text-xs">02</span>
                                Detail
                            </h3>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jenis Pelanggaran</label>
                                    <select name="violation_category_id" class="block w-full border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 shadow-sm sm:text-sm" required>
                                        <option value="" disabled selected>Pilih jenis...</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('violation_category_id') == $cat->id ? 'selected' : '' }}>
                                                [{{ $cat->kode }}] {{ $cat->deskripsi }} ({{ $cat->grup }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan</label>
                                    <textarea name="catatan" rows="3" class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm sm:text-sm">{{ old('catatan') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Bukti Foto</label>
                                    <input type="file" name="bukti_foto" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.student-violations.index') }}" class="px-6 py-3 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50">Batal</a>
                            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Tom Select --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect("#select-student", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            // Field apa saja yang bisa dicari (sesuai data attribute di option)
            searchField: ['text', 'nis', 'class'], 
            plugins: ['clear_button'],
            
            // Kustomisasi tampilan dropdown agar muncul NIS dan Kelas
            render: {
                option: function(data, escape) {
                    return '<div class="flex justify-between items-center py-1">' +
                        '<div>' +
                            '<span class="font-bold text-gray-800 block">' + escape(data.text) + '</span>' +
                            '<span class="text-xs text-gray-400">NIS: ' + escape(data.nis) + '</span>' +
                        '</div>' +
                        '<span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-1 rounded">' + escape(data.class) + '</span>' +
                    '</div>';
                },
                item: function(data, escape) {
                    return '<div>' + escape(data.text) + ' <span class="text-gray-400 text-xs">(' + escape(data.class) + ')</span></div>';
                }
            }
        });
    </script>
</x-app-layout>