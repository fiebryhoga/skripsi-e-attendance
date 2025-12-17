<x-app-layout>
    <x-slot name="header">Jadwal Pelajaran</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Atur Jadwal Pelajaran</h2>
                    <p class="text-sm text-gray-500">Pilih kelas untuk melihat atau mengubah jadwal.</p>
                </div>
                {{-- Search Bar Sederhana --}}
                <div x-data="{ search: '' }" class="relative w-full md:w-64">
                    <input type="text" x-model="search" placeholder="Cari Kelas..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($classrooms as $classroom)
                    <a href="{{ route('admin.schedules.show', $classroom) }}" 
                       class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                        
                        {{-- Color Stripe --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">{{ $classroom->name }}</h3>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">Tingkat SMA</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 mt-4 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            <span>{{ $classroom->students_count ?? 0 }} Siswa</span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-400 group-hover:text-indigo-500 transition-colors">Kelola Jadwal &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>