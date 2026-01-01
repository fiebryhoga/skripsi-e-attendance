<x-app-layout>
    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.attendances.index') }}" class="p-2 rounded-full border border-gray-200 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Presensi Kelas {{ $classroom->name }}</h1>
                        <p class="text-sm text-gray-500">Pilih mata pelajaran untuk input kehadiran.</p>
                    </div>
                </div>

                
                <div class="flex items-center bg-gray-50 p-1.5 rounded-xl border border-gray-200">
                    
                    
                    <a href="{{ route('admin.attendances.show', ['classroom' => $classroom->id, 'date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}" 
                       class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-white rounded-lg transition-all shadow-sm hover:shadow">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>

                    
                    <form method="GET" class="mx-2">
                        <input type="date" name="date" value="{{ $date }}" 
                               class="border-0 bg-transparent text-sm font-bold text-gray-700 focus:ring-0 cursor-pointer text-center"
                               onchange="this.form.submit()">
                    </form>

                    
                    <a href="{{ route('admin.attendances.show', ['classroom' => $classroom->id, 'date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}" 
                       class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-white rounded-lg transition-all shadow-sm hover:shadow">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>

            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-gray-700 flex items-center gap-2">
                        <span class="w-2 h-6 bg-indigo-500 rounded-full"></span>
                        
                        Jadwal Hari {{ $dayName }}, {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                    </h3>
                    
                    
                    @if($date == date('Y-m-d'))
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[8px] text-center md:text-xs font-medium rounded-full border border-green-200">Hari Ini</span>
                    @elseif($date < date('Y-m-d'))
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-[8px] text-center md:text-xs font-medium rounded-full border border-orange-200">Riwayat Lampau</span>
                    @else
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[8px] text-center md:text-xs font-medium rounded-full border border-blue-200">Akan Datang</span>
                    @endif
                </div>

                @forelse($schedules as $schedule)
                    <a href="{{ route('admin.attendances.create', ['classroom' => $classroom->id, 'schedule' => $schedule->id, 'date' => $date]) }}" 
                       class="block bg-white border border-gray-200 rounded-2xl p-5 hover:border-indigo-400 hover:shadow-lg hover:shadow-indigo-500/10 transition-all group">
                        
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex flex-col items-center justify-center border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    <span class="text-xs font-bold uppercase">Jam</span>
                                    <span class="text-lg font-black leading-none">{{ $schedule->jam_mulai }}</span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-800 group-hover:text-indigo-600 transition-colors">
                                        {{ $schedule->subject->name }}
                                    </h4>
                                    <p class="text-sm text-gray-500">{{ $schedule->teacher->name }}</p>
                                </div>
                            </div>

                            <div class="text-right">
                                @if($schedule->attendance_count > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Sudah Diabsen
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200 group-hover:bg-indigo-50 group-hover:text-indigo-600">
                                        Belum Diabsen &rarr;
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 border-dashed">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-gray-500 font-medium">Tidak ada jadwal pelajaran di hari {{ $dayName }} ({{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}).</p>
                        
                        @if($dayName == 'Minggu')
                            <p class="text-xs text-orange-500 font-bold mt-1">Hari Minggu Libur.</p>
                        @else
                            <p class="text-xs text-gray-400 mt-1">Pastikan jadwal pelajaran sudah diatur oleh Admin.</p>
                        @endif
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>