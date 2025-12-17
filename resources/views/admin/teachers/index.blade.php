<x-app-layout>
    <x-slot name="header">Direktori Guru & Staff</x-slot>

    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Pengajar</h2>
            <p class="text-sm text-gray-500 mt-1">Total {{ $teachers->total() }} akun aktif dalam sistem.</p>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <form action="{{ route('admin.teachers.index') }}" method="GET" class="relative flex-1 md:w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau NIP..." 
                       class="w-full pl-11 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm transition-all shadow-sm">
                <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
            
            <a href="{{ route('admin.teachers.create') }}" class="px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow-lg shadow-gray-900/20 hover:bg-black hover:-translate-y-0.5 transition-all flex items-center gap-2 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span class="hidden sm:inline">Tambah Baru</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-8 py-5 font-semibold">Profil Guru</th>
                        <th class="px-6 py-5 font-semibold">Jabatan & Akses</th>
                        <th class="px-6 py-5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($teachers as $teacher)
                        @include('admin.teachers._row', ['teacher' => $teacher])
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <span class="font-medium">Tidak ada data ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $teachers->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>