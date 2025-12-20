<x-app-layout>
    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome --}}
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-2xl">👋</div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->name }}</h1>
                    <p class="text-gray-500">Semangat mengajar hari ini!</p>
                </div>
            </div>

            {{-- Jadwal Hari Ini --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="font-bold text-lg">Jadwal Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('l') }})</h3>
                    <span class="bg-white/20 px-3 py-1 rounded-lg text-sm">{{ count($todaySchedules) }} Kelas</span>
                </div>
                
                <div class="p-6">
                    @if($todaySchedules->isEmpty())
                        <div class="text-center py-10">
                            <div class="inline-block p-4 rounded-full bg-green-50 text-green-600 mb-3">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="font-bold text-gray-800">Tidak ada jadwal mengajar hari ini.</h4>
                            <p class="text-sm text-gray-500 mt-1">Silakan persiapkan materi untuk besok.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($todaySchedules as $schedule)
                                <div class="flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md transition bg-white">
                                    {{-- Jam --}}
                                    <div class="w-20 text-center border-r border-gray-100 pr-4 mr-4">
                                        <div class="font-black text-lg text-gray-800">{{ $schedule->jam_mulai }}</div>
                                        <div class="text-xs text-gray-400">s/d {{ $schedule->jam_selesai }}</div>
                                    </div>
                                    
                                    {{-- Info Kelas --}}
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs font-bold rounded">Kelas {{ $schedule->classroom->name }}</span>
                                            <span class="text-xs text-gray-400 uppercase tracking-wider font-bold">{{ $schedule->subject->name }}</span>
                                        </div>
                                        <div class="flex gap-2 mt-2">
                                            <a href="{{ route('admin.attendances.create', ['schedule_id' => $schedule->id]) }}" class="text-xs font-bold text-white bg-indigo-600 px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition">
                                                Isi Presensi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>