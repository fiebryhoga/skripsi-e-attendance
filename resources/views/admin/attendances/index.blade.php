<x-app-layout>
    <x-slot name="header">Presensi Siswa</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Presensi Harian</h2>
                <p class="text-sm text-gray-500">Pilih kelas untuk melakukan rekap kehadiran.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($classrooms as $classroom)
                    <a href="{{ route('admin.attendances.show', $classroom) }}" 
                       class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-indigo-200 hover:-translate-y-1 transition-all duration-300 group flex flex-col h-full">
                        
                        
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-3xl font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">{{ $classroom->name }}</span>
                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        </div>
                        
                        
                        @if(Auth::user()->hasRole(\App\Enums\UserRole::ADMIN))
                            
                            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $classroom->students_count }} Siswa</span>
                                <span class="text-indigo-500 font-semibold group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </div>

                        @else
                            
                            <div class="mt-2 space-y-2 flex-1">
                                @foreach($classroom->schedules as $schedule)
                                    <div class="bg-gray-50 rounded-lg p-2 border border-gray-100 text-sm">
                                        
                                        <div class="font-bold text-gray-700 text-xs uppercase mb-1">
                                            {{ $schedule->subject->name }}
                                        </div>
                                        
                                        
                                        <div class="flex justify-between items-center text-gray-500 text-xs">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                {{ $schedule->day }}
                                            </span>
                                            <span class="bg-white px-1.5 py-0.5 rounded border border-gray-200 font-mono text-[10px]">
                                                Jam {{ $schedule->jam_mulai }}-{{ $schedule->jam_selesai }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-gray-50 text-right">
                                <span class="text-xs font-semibold text-indigo-500 group-hover:underline">Buka Presensi &rarr;</span>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>