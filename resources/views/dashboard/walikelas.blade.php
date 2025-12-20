<x-app-layout>
    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header Kelas --}}
            <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold">Kelas {{ $classroom->name }}</h2>
                    <p class="text-indigo-100 mt-2">Laporan perkembangan siswa per {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                </div>
                {{-- Dekorasi --}}
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-xs font-bold uppercase">Total Siswa</div>
                    <div class="text-3xl font-black text-gray-800 mt-2">{{ $totalStudents }}</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-xs font-bold uppercase">Tidak Hadir Hari Ini</div>
                    <div class="text-3xl font-black {{ $todayAbsence > 0 ? 'text-orange-500' : 'text-green-500' }} mt-2">{{ $todayAbsence }}</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-xs font-bold uppercase">Pelanggaran Bulan Ini</div>
                    <div class="text-3xl font-black {{ $monthViolations > 0 ? 'text-red-500' : 'text-gray-800' }} mt-2">{{ $monthViolations }}</div>
                </div>
            </div>

            {{-- Tabel Siswa Perlu Perhatian --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-800 text-lg">Siswa Perlu Perhatian</h3>
                    <a href="{{ route('admin.homeroom.index') }}" class="text-sm text-indigo-600 font-bold hover:underline">Lihat Detail &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                            <tr>
                                <th class="px-4 py-3">Nama Siswa</th>
                                <th class="px-4 py-3 text-center text-red-600">Total Pelanggaran</th>
                                <th class="px-4 py-3 text-center text-orange-600">Total Alpha</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($problematicStudents as $student)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $student->name }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-red-600">{{ $student->violations_count }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-orange-600">{{ $student->alpha_count }}</td>
                                    <td class="px-4 py-3">
                                        @if($student->violations_count > 5 || $student->alpha_count > 3)
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">Panggil Ortu</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold">Pantau</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if($problematicStudents->isEmpty())
                                <tr><td colspan="4" class="text-center py-4 text-gray-400">Kelas Aman. Tidak ada siswa bermasalah signifikan.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>