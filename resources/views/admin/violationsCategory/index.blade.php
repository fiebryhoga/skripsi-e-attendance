<x-app-layout>
    <div class="">
        <div class="">
            
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-10">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Master Kategori</h2>
                    <p class="text-sm text-gray-500 mt-2">Daftar referensi jenis pelanggaran tata tertib.</p>
                </div>
                <a href="{{ route('admin.violations.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white font-bold rounded-xl hover:from-orange-600 hover:to-red-700 shadow-lg shadow-orange-500/30 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Kategori
                </a>
            </div>

            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-lg text-green-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-400 hover:text-green-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @endif

            
            <div class="space-y-8">
                @forelse($groupedViolations as $group => $violations)
                    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        
                        
                        <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-100 to-orange-50 text-orange-600 font-extrabold text-lg shadow-inner">
                                    {{ $group }}
                                </span>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">
                                        @if($group == 'A') Pelanggaran Ringan
                                        @elseif($group == 'B') Pelanggaran Sedang
                                        @elseif($group == 'C') Pelanggaran Berat
                                        @else Grup Lainnya @endif
                                    </h3>
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total {{ count($violations) }} Item</span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-white border-b border-gray-100 text-xs uppercase tracking-wider text-gray-400">
                                        <th class="px-8 py-4 font-bold w-24">Kode</th>
                                        <th class="px-6 py-4 font-bold">Deskripsi Pelanggaran</th>
                                        <th class="px-8 py-4 font-bold w-32 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($violations as $item)
                                        <tr class="hover:bg-orange-50/30 transition-colors duration-150 group">
                                            <td class="px-8 py-4 align-top">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-700 transition-colors">
                                                    {{ $item->kode }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 align-top">
                                                <p class="text-sm font-medium text-gray-700 leading-relaxed">{{ $item->deskripsi }}</p>
                                            </td>
                                            <td class="px-8 py-4 align-top text-right">
                                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <a href="{{ route('admin.violations.edit', $item->id) }}" class="p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </a>
                                                    <form action="{{ route('admin.violations.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm">
                        <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Belum ada data</h3>
                        <p class="text-gray-500 text-sm mt-1">Silakan tambahkan kategori pelanggaran baru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>