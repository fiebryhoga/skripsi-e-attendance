<x-app-layout>
    <x-slot name="header">Data Kelas</x-slot>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Kelas</h2>
            <p class="text-sm text-gray-500 mt-1">Total {{ $classrooms->count() }} kelas terdaftar.</p>
        </div>

        <form method="GET" class="relative w-full md:w-64">
            <input type="text" name="search" value="{{ request('search') }}"
                class="pl-10 pr-4 py-2.5 w-full text-sm bg-white border border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-sm"
                placeholder="Cari Kelas (ex: X-A)...">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </form>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-green-500 hover:text-green-700">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($classrooms as $classroom)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-indigo-100 hover:-translate-y-1 transition-all duration-300 group overflow-hidden">
                
                @php
                    $bgClass = str_contains($classroom->name, 'X-') ? 'bg-blue-500' : (str_contains($classroom->name, 'XI-') ? 'bg-indigo-500' : 'bg-violet-600');
                @endphp
                <div class="{{ $bgClass }} h-2"></div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 tracking-tight">{{ $classroom->name }}</h3>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">Tingkat SMA</p>
                        </div>
                        <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="text-sm font-bold text-gray-700">{{ $classroom->students->count() }}</span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-3 p-3 rounded-xl {{ $classroom->teacher ? 'bg-indigo-50 border border-indigo-100' : 'bg-gray-50 border border-gray-100 border-dashed' }}">
                        @if($classroom->teacher)
                            <img class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm"
                                src="{{ $classroom->teacher->avatar ? Storage::url($classroom->teacher->avatar) : 'https://ui-avatars.com/api/?name='.$classroom->teacher->name }}" alt="">
                            <div class="overflow-hidden">
                                <p class="text-xs text-gray-500 mb-0.5">Wali Kelas</p>
                                <p class="text-sm font-bold text-gray-800 truncate">{{ Str::limit($classroom->teacher->name, 15) }}</p>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 italic">Belum ditentukan</p>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('admin.classrooms.show', $classroom) }}" class="mt-4 flex items-center justify-center w-full py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all">
                        Kelola Kelas
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>