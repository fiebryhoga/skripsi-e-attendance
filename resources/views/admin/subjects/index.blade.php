<x-app-layout>
    <x-slot name="header">Mata Pelajaran</x-slot>

    
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl flex justify-between items-center shadow-sm">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </span>
            <button @click="show = false">&times;</button>
        </div>
    @endif

    
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl shadow-sm">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 p-6 sticky top-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Tambah Mapel</h3>
                        <p class="text-xs text-gray-500">Buat mata pelajaran baru.</p>
                    </div>
                </div>

                <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kode Mapel</label>
                        <input type="text" name="kode" placeholder="MTK-01" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 px-4 font-mono transition-all" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Mata Pelajaran</label>
                        <input type="text" name="name" placeholder="Matematika Wajib" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 px-4 transition-all" required>
                    </div>
                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                        Simpan Mapel
                    </button>
                </form>
            </div>
        </div>

        
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden">
                
                
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/30">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Mata Pelajaran</h3>
                    <form method="GET" class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="pl-10 pr-4 py-2 w-full text-sm bg-white border border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all" 
                               placeholder="Cari Mapel...">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4 w-28">Kode</th>
                                <th class="px-6 py-4">Nama Mata Pelajaran</th>
                                <th class="px-6 py-4 w-24 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm" x-data="{ editingId: null }">
                            @forelse($subjects as $subject)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    
                                    
                                    <template x-if="editingId !== {{ $subject->id }}">
                                        <td class="px-6 py-4 font-mono text-indigo-600 font-bold bg-indigo-50/50 rounded-r-lg">
                                            {{ $subject->kode }}
                                        </td>
                                    </template>
                                    <template x-if="editingId !== {{ $subject->id }}">
                                        <td class="px-6 py-4 font-medium text-gray-800">
                                            {{ $subject->name }}
                                        </td>
                                    </template>
                                    <template x-if="editingId !== {{ $subject->id }}">
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button @click="editingId = {{ $subject->id }}" class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 p-1.5 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                </button>
                                                <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" onsubmit="return confirm('Hapus Mapel ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 p-1.5 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </template>

                                    
                                    <template x-if="editingId === {{ $subject->id }}">
                                        <td colspan="3" class="px-6 py-4 bg-yellow-50/50">
                                            <form action="{{ route('admin.subjects.update', $subject) }}" method="POST" class="flex gap-3 items-center w-full">
                                                @csrf @method('PUT')
                                                <div class="w-24">
                                                    <input type="text" name="kode" value="{{ $subject->kode }}" class="w-full rounded-lg border-gray-300 text-xs py-2">
                                                </div>
                                                <div class="flex-1">
                                                    <input type="text" name="name" value="{{ $subject->name }}" class="w-full rounded-lg border-gray-300 text-xs py-2">
                                                </div>
                                                <div class="flex gap-1">
                                                    <button type="submit" class="p-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    </button>
                                                    <button type="button" @click="editingId = null" class="p-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </template>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                        Belum ada mata pelajaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $subjects->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>