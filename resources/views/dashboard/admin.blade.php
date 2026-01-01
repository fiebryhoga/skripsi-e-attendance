<x-app-layout>
    <div class="bg-gray-50/50 min-h-screen">
        <div class="space-y-8">

            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                        Dashboard Ikhtisar
                    </h1>
                    <p class="text-gray-500 mt-1 text-sm">
                        Selamat datang kembali, <span class="font-bold text-indigo-600">{{ Auth::user()->name }}</span>! Berikut laporan hari ini.
                    </p>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Siswa</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-gray-800">{{ $totalStudents }}</h3>
                        <span class="text-xs text-green-500 font-semibold bg-green-50 px-2 py-1 rounded-lg">Aktif</span>
                    </div>
                </div>

                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Kasus</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-gray-800">{{ $totalViolations }}</h3>
                        <span class="text-xs text-gray-400">Sepanjang Waktu</span>
                    </div>
                </div>

                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow relative overflow-hidden">
                    <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-orange-400 to-orange-600"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-orange-500 uppercase tracking-wider">Hari Ini</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-gray-800">{{ $todayViolations }}</h3>
                        <span class="text-xs text-orange-600 font-semibold bg-orange-50 px-2 py-1 rounded-lg">Kasus Baru</span>
                    </div>
                </div>

                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Kelas</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-gray-800">{{ $totalClasses }}</h3>
                        <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2 py-1 rounded-lg">Rombel</span>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                            <h3 class="font-bold text-gray-800 text-lg">Riwayat Terbaru</h3>
                            <a href="{{ route('admin.student-violations.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua &rarr;</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold">Siswa</th>
                                        <th class="px-6 py-3 font-semibold">Pelanggaran</th>
                                        <th class="px-6 py-3 font-semibold text-right">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($recentViolations as $violation)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                                        {{ substr($violation->student->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-bold text-gray-800">{{ $violation->student->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $violation->student->classroom->name ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">
                                                    {{ $violation->category->kode }}
                                                </span>
                                                <span class="text-xs text-gray-500 ml-2">{{ Str::limit($violation->category->deskripsi, 20) }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-xs text-gray-500">
                                                {{ $violation->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada data pelanggaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Top 5 Pelanggaran
                        </h3>
                        <p class="text-xs text-gray-500 mb-6">Siswa dengan frekuensi pelanggaran tertinggi.</p>

                        <div class="space-y-5">
                            @forelse($topViolators as $index => $student)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 flex items-center justify-center rounded-lg font-bold text-sm {{ $index == 0 ? 'bg-yellow-100 text-yellow-700' : ($index == 1 ? 'bg-gray-100 text-gray-700' : 'bg-orange-50 text-orange-700') }}">
                                            #{{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">{{ $student->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $student->classroom->name ?? 'No Class' }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-black text-red-600">{{ $student->violations_count }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase">Kasus</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-400 text-sm">Data masih kosong.</div>
                            @endforelse
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.students.index') }}" class="block w-full py-2 bg-gray-50 text-center text-xs font-bold text-gray-600 rounded-lg hover:bg-gray-100 transition">
                                Lihat Semua Siswa
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>