<x-app-layout>
    <x-slot name="header">Direktori Guru</x-slot>

    <div class="space-y-6" x-data="{ showImportModal: false, fileName: null }">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Daftar Pengajar</h2>
                <p class="text-gray-500 mt-2">Kelola data {{ $teachers->total() }} guru yang terdaftar dalam sistem.</p>
            </div>
            
            <div class="flex gap-3">
                <button @click="showImportModal = true; fileName = null" 
                    class="group flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 font-semibold rounded-xl hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 transition-all shadow-sm">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Import Excel</span>
                </button>

                <a href="{{ route('admin.teachers.create') }}" 
                   class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Guru Baru</span>
                </a>
            </div>
        </div>

        <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-gray-200">
            <form action="{{ route('admin.teachers.index') }}" method="GET">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" 
                           class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:bg-white focus:border-indigo-500 transition-colors" 
                           placeholder="Cari berdasarkan Nama, NIP, atau Email..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="hidden"></button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-200">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Profil Pengajar</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jabatan & Peran</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($teachers as $teacher)
                            @include('admin.teachers._table_rows', ['teacher' => $teacher])
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900">Tidak ada data ditemukan</h3>
                                        <p class="text-gray-500 mt-1 max-w-sm">Coba ubah kata kunci pencarian Anda atau tambahkan pengajar baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($teachers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $teachers->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <div x-show="showImportModal" style="display: none;" 
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900/50 backdrop-blur-sm p-4"
        x-transition.opacity>
        
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden" @click.away="showImportModal = false">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900">Import Data Guru</h3>
                <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-5">
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-indigo-50 hover:border-indigo-400 transition-all group">
                                <div x-show="!fileName" class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                    <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                    <p class="mb-2 text-sm text-gray-600 font-medium">Klik untuk upload file Excel</p>
                                    <p class="text-xs text-gray-400">XLSX, XLS (Maks. 5MB)</p>
                                </div>
                                <div x-show="fileName" class="flex flex-col items-center justify-center pt-5 pb-6 text-center" style="display: none;">
                                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <p class="text-sm text-gray-900 font-bold px-4 truncate w-full" x-text="fileName"></p>
                                    <p class="text-xs text-indigo-500 mt-1 font-medium">Klik untuk ganti</p>
                                </div>
                                <input id="dropzone-file" name="file" type="file" class="hidden" required accept=".xlsx,.xls" @change="fileName = $event.target.files[0].name" />
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('admin.teachers.template') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download Template
                        </a>
                        <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl text-sm px-6 py-2.5 shadow-lg shadow-indigo-200 transition-all">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>