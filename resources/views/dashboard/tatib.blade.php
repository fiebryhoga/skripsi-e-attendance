<x-app-layout>
    <div class="bg-gray-50/50 min-h-screen">
        <div class="space-y-8">

            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800">Dashboard Kedisiplinan</h1>
                    <p class="text-gray-500 text-sm">Pantau ketertiban siswa hari ini.</p>
                </div>
                <a href="{{ route('admin.student-violations.create') }}" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl shadow-lg shadow-red-500/30 hover:bg-red-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Catat Pelanggaran Baru
                </a>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="text-xs font-bold text-gray-400 uppercase">Kasus Hari Ini</div>
                    <div class="text-4xl font-black text-gray-800 mt-2">{{ $todayViolationsCount }}</div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="text-xs font-bold text-gray-400 uppercase">Kasus Bulan Ini</div>
                    <div class="text-4xl font-black text-gray-800 mt-2">{{ $monthViolationsCount }}</div>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-orange-600 p-6 rounded-2xl shadow-lg text-white">
                    <div class="text-xs font-bold text-red-100 uppercase">Pelanggaran Terbanyak (Top 1)</div>
                    <div class="text-xl font-bold mt-2">{{ $topCategories->first()->name ?? 'Belum ada data' }}</div>
                    <div class="text-sm opacity-80 mt-1">{{ $topCategories->first()->violations_count ?? 0 }} Kasus</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Kejadian Hari Ini
                    </h3>
                    
                    @if($todayViolationsList->isEmpty())
                        <div class="text-center py-8 text-gray-400">Belum ada pelanggaran tercatat hari ini.</div>
                    @else
                        <div class="space-y-4">
                            @foreach($todayViolationsList as $v)
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                                    <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ substr($v->student->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <h4 class="font-bold text-gray-800">{{ $v->student->name }} <span class="text-xs font-normal text-gray-500">({{ $v->student->classroom->name }})</span></h4>
                                            <span class="text-xs text-gray-400">{{ $v->created_at->format('H:i') }}</span>
                                        </div>
                                        <p class="text-sm text-red-600 font-medium mt-1">{{ $v->category->deskripsi }}</p>
                                        @if($v->catatan)
                                            <p class="text-xs text-gray-500 italic mt-1">"{{ $v->catatan }}"</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-bold text-gray-800 text-lg mb-4">Tren Pelanggaran</h3>
                    <ul class="space-y-3">
                        @foreach($topCategories as $cat)
                            <li class="flex justify-between items-center text-sm border-b border-gray-50 pb-2">
                                <span class="text-gray-600 truncate max-w-[150px]">{{ $cat->deskripsi }}</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded font-bold text-xs">{{ $cat->violations_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>