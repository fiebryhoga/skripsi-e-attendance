<x-app-layout>
    <x-slot name="header">Data Siswa</x-slot>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8" 
         x-data="{ showImportModal: false, fileName: null }">
        
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Total {{ $students->total() }} siswa terdaftar dalam sistem.</p>
        </div>
        
        <div class="flex gap-2">
            <button @click="showImportModal = true; fileName = null" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:text-indigo-600 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Import Excel
            </button>

            <a href="{{ route('admin.students.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah
            </a>
        </div>

        <div x-show="showImportModal" style="display: none;" 
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/60 backdrop-blur-sm p-4 md:inset-0"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl" @click.away="showImportModal = false">
                <div class="flex items-center justify-between p-5 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800">Import Data Siswa</h3>
                    <button @click="showImportModal = false" class="text-gray-400 hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Upload File Excel</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-indigo-50 hover:border-indigo-400 transition-all group">
                                    
                                    <div x-show="!fileName" class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                        <p class="text-xs text-gray-500">XLSX, XLS, atau CSV (MAX. 50MB)</p>
                                    </div>

                                    <div x-show="fileName" class="flex flex-col items-center justify-center pt-5 pb-6" style="display: none;">
                                        <svg class="w-10 h-10 mb-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm text-gray-900 font-semibold" x-text="fileName"></p>
                                        <p class="text-xs text-indigo-500 mt-1">Klik untuk ganti file</p>
                                    </div>

                                    <input id="dropzone-file" name="file" type="file" class="hidden" required accept=".xlsx,.xls,.csv" 
                                           @change="fileName = $event.target.files[0].name" />
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm mb-6">
                            <span class="text-gray-500">Belum punya format?</span>
                            <a href="{{ route('admin.students.template') }}" class="text-indigo-600 hover:text-indigo-800 font-medium hover:underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                Download Template
                            </a>
                        </div>
                        <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl text-sm px-5 py-3 text-center shadow-lg shadow-indigo-500/30 transition-all">
                            Mulai Import Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6">
       <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative md:col-span-1">
                <input type="text" name="search" id="searchInput" 
                       class="pl-10 pr-4 py-2.5 w-full text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all" 
                       placeholder="Cari Nama / NIS..." value="{{ request('search') }}">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <div>
                <select name="angkatan" id="angkatanInput" class="w-full py-2.5 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all cursor-pointer">
                    <option value="">Semua Angkatan</option>
                    @for ($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ request('angkatan') == $year ? 'selected' : '' }}>Angkatan {{ $year }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <select name="gender" id="genderInput" class="w-full py-2.5 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all cursor-pointer">
                    <option value="">Semua Gender</option>
                    <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div>
                <select name="classroom_id" id="classInput" class="w-full py-2.5 px-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($classrooms as $class)
                        <option value="{{ $class->id }}" {{ request('classroom_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 overflow-hidden border border-gray-100 relative min-h-[300px]">
        <div id="loadingSpinner" class="hidden absolute inset-0 bg-white/60 z-10 items-center justify-center backdrop-blur-sm">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-8 py-5 font-semibold">Profil Siswa</th>
                        <th class="px-6 py-5 font-semibold">Identitas</th>
                        <th class="px-6 py-5 font-semibold">Gender</th>
                        <th class="px-6 py-5 font-semibold">Kelas</th>
                        <th class="px-6 py-5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="studentsTableBody" class="divide-y divide-gray-50 text-sm">
                    @include('admin.students._table_rows', ['students' => $students])
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filterForm');
            const searchInput = document.getElementById('searchInput');
            const angkatanInput = document.getElementById('angkatanInput');
            const genderInput = document.getElementById('genderInput');
            const classInput = document.getElementById('classInput');
            const tableBody = document.getElementById('studentsTableBody');
            const loadingSpinner = document.getElementById('loadingSpinner');

            // Fungsi Fetch Data
            function fetchStudents() {
                loadingSpinner.classList.remove('hidden');

                const params = new URLSearchParams(new FormData(filterForm)).toString();
                
                fetch(`{{ route('admin.students.index') }}?${params}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    loadingSpinner.classList.add('hidden');
                    window.history.pushState(null, '', `?${params}`);
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingSpinner.classList.add('hidden');
                });
            }

            // Event Listeners
            let debounceTimer;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchStudents, 300);
            });

            angkatanInput.addEventListener('change', fetchStudents);
            genderInput.addEventListener('change', fetchStudents);
            
            // Event Listener untuk Kelas
            classInput.addEventListener('change', fetchStudents);
        });
    </script>
</x-app-layout>