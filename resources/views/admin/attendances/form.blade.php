<x-app-layout>
    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Navigasi Balik --}}
            <a href="{{ route('admin.attendances.show', ['classroom' => $classroom->id, 'date' => $date]) }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Mapel
            </a>

            {{-- Header Info --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg uppercase tracking-wide">
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                        </span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg uppercase tracking-wide">
                            Jam ke-{{ $schedule->jam_mulai }} s.d {{ $schedule->jam_selesai }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-extrabold text-gray-800">{{ $schedule->subject->name }}</h1>
                    <p class="text-gray-500 mt-1">Pengajar: <span class="font-medium text-gray-700">{{ $schedule->teacher->name }}</span></p>
                </div>

                {{-- Summary Stats --}}
                <div class="flex gap-2">
                    <div class="text-center px-4 py-2 bg-green-50 rounded-xl border border-green-100">
                        <span class="block text-xl font-bold text-green-600">{{ $summary['hadir'] }}</span>
                        <span class="text-[10px] uppercase font-bold text-green-400">Hadir</span>
                    </div>
                    <div class="text-center px-4 py-2 bg-blue-50 rounded-xl border border-blue-100">
                        <span class="block text-xl font-bold text-blue-600">{{ $summary['sakit'] }}</span>
                        <span class="text-[10px] uppercase font-bold text-blue-400">Sakit</span>
                    </div>
                    <div class="text-center px-4 py-2 bg-orange-50 rounded-xl border border-orange-100">
                        <span class="block text-xl font-bold text-orange-600">{{ $summary['izin'] }}</span>
                        <span class="text-[10px] uppercase font-bold text-orange-400">Izin</span>
                    </div>
                    <div class="text-center px-4 py-2 bg-red-50 rounded-xl border border-red-100">
                        <span class="block text-xl font-bold text-red-600">{{ $summary['alpha'] }}</span>
                        <span class="text-[10px] uppercase font-bold text-red-400">Alpha</span>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- FORM UTAMA --}}
            <form action="{{ route('admin.attendances.store', ['classroom' => $classroom->id, 'schedule' => $schedule->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">

                <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden">
                    
                    {{-- Toolbar --}}
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center" x-data>
                        <h3 class="font-bold text-gray-700">Daftar Siswa ({{ $students->count() }})</h3>
                        <button type="button" 
                                @click="document.querySelectorAll('input[value=\'Hadir\']').forEach(el => el.checked = true)"
                                class="text-xs font-bold bg-white border border-gray-300 px-3 py-1.5 rounded-lg text-gray-600 hover:text-indigo-600 hover:border-indigo-300 transition shadow-sm">
                            Set Semua Hadir
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-white text-gray-500 text-xs uppercase border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 font-bold">Nama Siswa</th>
                                    <th class="px-6 py-4 font-bold text-center">Status Kehadiran</th>
                                    <th class="px-6 py-4 font-bold">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($students as $student)
                                    @php
                                        $currentStatus = $student->attendance_today ? $student->attendance_today->status : null; 
                                        $currentNote = $student->attendance_today ? $student->attendance_today->note : '';
                                    @endphp
                                    <tr class="hover:bg-indigo-50/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-xs font-bold text-indigo-600">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-800 text-sm">{{ $student->name }}</div>
                                                    <div class="text-xs text-gray-400">{{ $student->nis }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="inline-flex bg-gray-100 p-1 rounded-lg">
                                                @foreach(['Hadir', 'Sakit', 'Izin', 'Alpha'] as $statusOption)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" 
                                                               name="attendances[{{ $student->id }}][status]" 
                                                               value="{{ $statusOption }}" 
                                                               class="peer sr-only"
                                                               {{ ($currentStatus == $statusOption) || (!$currentStatus && $statusOption == 'Hadir') ? 'checked' : '' }}
                                                        >
                                                        <span class="block px-4 py-1.5 rounded-md text-xs font-bold transition-all
                                                            peer-checked:shadow-sm
                                                            {{ $statusOption == 'Hadir' ? 'peer-checked:bg-green-500 peer-checked:text-white text-gray-500 hover:text-green-600' : '' }}
                                                            {{ $statusOption == 'Sakit' ? 'peer-checked:bg-blue-500 peer-checked:text-white text-gray-500 hover:text-blue-600' : '' }}
                                                            {{ $statusOption == 'Izin' ? 'peer-checked:bg-orange-500 peer-checked:text-white text-gray-500 hover:text-orange-600' : '' }}
                                                            {{ $statusOption == 'Alpha' ? 'peer-checked:bg-red-500 peer-checked:text-white text-gray-500 hover:text-red-600' : '' }}
                                                        ">
                                                            {{ $statusOption }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="attendances[{{ $student->id }}][note]" 
                                                   value="{{ $currentNote }}"
                                                   class="w-full text-xs border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-300 bg-gray-50 focus:bg-white transition-colors" 
                                                   placeholder="Ket. tambahan...">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-400">Tidak ada siswa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                            Simpan Data Presensi
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>