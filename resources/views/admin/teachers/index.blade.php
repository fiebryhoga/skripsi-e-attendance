<x-app-layout>
    <x-slot name="header">Direktori Guru</x-slot>

    {{-- State Alpine.js untuk Modal --}}
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4 mb-8" 
         x-data="{ showImportModal: false, fileName: null }">
        
        {{-- Judul & Total Data --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pengajar</h2>
            <p class="text-sm text-gray-500 mt-1">Total {{ $teachers->total() }} guru terdaftar dalam sistem.</p>
        </div>
        
        {{-- Tombol Action (Import & Tambah) --}}
        <div class="flex gap-2">
            {{-- Tombol Trigger Modal --}}
            <button @click="showImportModal = true; fileName = null" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:text-indigo-600 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Import Excel
            </button>

            {{-- Tombol Tambah Manual --}}
            <a href="{{ route('admin.teachers.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah
            </a>
        </div>

        {{-- MODAL IMPORT (Style Modern) --}}
        <div x-show="showImportModal" style="display: none;" 
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/60 backdrop-blur-sm p-4 md:inset-0"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl" @click.away="showImportModal = false">
                {{-- Header Modal --}}
                <div class="flex items-center justify-between p-5 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800">Import Data Guru</h3>
                    <button @click="showImportModal = false" class="text-gray-400 hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="p-6">
                    <form action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Upload File Excel</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-indigo-50 hover:border-indigo-400 transition-all group">
                                    
                                    {{-- State Awal (Belum ada file) --}}
                                    <div x-show="!fileName" class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                        <p class="text-xs text-gray-500">XLSX, XLS (MAX. 5MB)</p>
                                    </div>

                                    {{-- State Ada File (File Selected) --}}
                                    <div x-show="fileName" class="flex flex-col items-center justify-center pt-5 pb-6" style="display: none;">
                                        <svg class="w-10 h-10 mb-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm text-gray-900 font-semibold" x-text="fileName"></p>
                                        <p class="text-xs text-indigo-500 mt-1">Klik untuk ganti file</p>
                                    </div>

                                    <input id="dropzone-file" name="file" type="file" class="hidden" required accept=".xlsx,.xls" 
                                           @change="fileName = $event.target.files[0].name" />
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between text-sm mb-6">
                            <span class="text-gray-500">Belum punya format?</span>
                            <a href="{{ route('admin.teachers.template') }}" class="text-indigo-600 hover:text-indigo-800 font-medium hover:underline flex items-center gap-1">
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
    
    {{-- Notifikasi Sukses/Error --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3">
            <div class="bg-green-100 p-2 rounded-full">
                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center gap-3">
            <div class="bg-red-100 p-2 rounded-full">
                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Filter & Search Bar --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6">
       <form action="{{ route('admin.teachers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative md:col-span-4">
                <input type="text" name="search" id="searchInput" 
                       class="pl-10 pr-4 py-2.5 w-full text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all" 
                       placeholder="Cari Nama Pengajar / NIP..." value="{{ request('search') }}">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            {{-- Tombol submit hidden agar bisa enter --}}
            <button type="submit" class="hidden"></button>
        </form>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 overflow-hidden border border-gray-100 relative min-h-[300px]">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-8 py-5 font-semibold">Pengajar & Kontak</th>
                        <th class="px-6 py-5 font-semibold">Jabatan / Role</th>
                        <th class="px-6 py-5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($teachers as $teacher)
                        @include('admin.teachers._table_rows', ['teacher' => $teacher])
                    @empty
                         <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <span class="font-medium">Tidak ada data guru ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $teachers->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>