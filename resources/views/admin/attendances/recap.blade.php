<x-app-layout>
    <x-slot name="header">Rekap Presensi</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. CARD FILTER (Pilih Kelas & Tanggal) --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    Filter Data
                </h2>
                
                <form method="GET" action="{{ route('admin.attendances.recap') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    {{-- Input Kelas --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kelas</label>
                        {{-- TAMBAHKAN ID: classroom_select --}}
                        <select name="classroom_id" id="classroom_select" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classrooms as $c)
                                <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pilih Mapel --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mata Pelajaran</label>
                        {{-- TAMBAHKAN ID: subject_select --}}
                        <select name="subject_id" id="subject_select" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-gray-50" required {{ request('classroom_id') ? '' : 'disabled' }}>
                            <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                            
                            {{-- Loop ini hanya berjalan jika User sudah Submit (Reload), agar pilihan tidak hilang --}}
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Input Tanggal --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-t')) }}" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 text-sm" required>
                    </div>

                    <div>
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">
                            Tampilkan Data
                        </button>
                    </div>
                </form>
            </div>

            @if(request()->has('classroom_id'))
                
                {{-- HEADER LAPORAN --}}
                <div class="flex flex-col md:flex-row justify-between items-end border-b border-gray-200 pb-4">
                    <div>
                        <h3 class="font-extrabold text-2xl text-gray-800">Laporan Presensi Siswa</h3>
                        <p class="text-gray-500 text-sm mt-1">
                            Kelas <span class="font-bold text-gray-800">{{ $selectedClassroom->name }}</span> &bull; 
                            Mapel <span class="font-bold text-gray-800">{{ $selectedSubject->name }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Periode: {{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    {{-- GANTI TOMBOL LAMA DENGAN INI --}}
                    <a href="{{ route('admin.attendances.recap.download', request()->all()) }}" 
                    class="mt-4 md:mt-0 px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 flex items-center gap-2 shadow-lg shadow-green-500/30 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Excel
                    </a>
                </div>

                {{-- 2. TABEL RINGKASAN (TOTAL ANGKA) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-3 bg-indigo-50 border-b border-indigo-100">
                        <h4 class="font-bold text-indigo-900 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" /></svg>
                            Ringkasan Kehadiran (Total)
                        </h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-white border-b border-gray-200 text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-6 py-3 font-bold w-10">No</th>
                                    <th class="px-6 py-3 font-bold">Nama Siswa</th>
                                    <th class="px-6 py-3 font-bold text-center w-24 bg-green-50 text-green-700">Hadir</th>
                                    <th class="px-6 py-3 font-bold text-center w-24 bg-blue-50 text-blue-700">Sakit</th>
                                    <th class="px-6 py-3 font-bold text-center w-24 bg-orange-50 text-orange-700">Izin</th>
                                    <th class="px-6 py-3 font-bold text-center w-24 bg-red-50 text-red-700">Alpha</th>
                                    <th class="px-6 py-3 font-bold text-center w-32">Persentase</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm">
                                @foreach($students as $student)
                                    @php
                                        // Hitung Total di PHP Blade langsung
                                        $h = $student->attendances->where('status', 'Hadir')->count();
                                        $s = $student->attendances->where('status', 'Sakit')->count();
                                        $i = $student->attendances->where('status', 'Izin')->count();
                                        $a = $student->attendances->where('status', 'Alpha')->count();
                                        $totalPertemuan = count($dates);
                                        $persentase = $totalPertemuan > 0 ? round(($h / $totalPertemuan) * 100) : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3 text-center text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-3 font-bold text-gray-800">{{ $student->name }}</td>
                                        <td class="px-6 py-3 text-center font-bold text-green-600 bg-green-50/30">{{ $h }}</td>
                                        <td class="px-6 py-3 text-center font-bold text-blue-600 bg-blue-50/30">{{ $s }}</td>
                                        <td class="px-6 py-3 text-center font-bold text-orange-600 bg-orange-50/30">{{ $i }}</td>
                                        <td class="px-6 py-3 text-center font-bold text-red-600 bg-red-50/30">{{ $a }}</td>
                                        <td class="px-6 py-3 text-center font-bold text-gray-700">
                                            <div class="flex items-center gap-2 justify-center">
                                                <div class="w-10 bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $persentase }}%"></div>
                                                </div>
                                                <span class="text-xs">{{ $persentase }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 3. TABEL DETAIL HARIAN (DI BAWAHNYA) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
                    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                        <h4 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Rincian Detail per Pertemuan
                        </h4>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                                    <th class="px-4 py-3 font-bold sticky left-0 bg-gray-100 z-10 w-10 border-r border-gray-200">No</th>
                                    <th class="px-4 py-3 font-bold sticky left-10 bg-gray-100 z-10 w-48 border-r border-gray-200 shadow-sm">Nama Siswa</th>
                                    
                                    {{-- LOOP HEADER TANGGAL (DETAIL) --}}
                                    @foreach($dates as $date)
                                        <th class="px-2 py-3 text-center min-w-[50px] border-r border-gray-200 bg-white">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
                                                <span class="text-[9px] text-gray-400">{{ \Carbon\Carbon::parse($date)->format('M') }}</span>
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                @forelse($students as $index => $student)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center text-gray-400 sticky left-0 bg-white z-10 border-r border-gray-100">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-800 sticky left-10 bg-white z-10 border-r border-gray-100 shadow-sm">
                                            {{ $student->name }}
                                        </td>

                                        {{-- LOOP ISI DETAIL PER TANGGAL --}}
                                        @foreach($dates as $date)
                                            @php
                                                // Cari status pada tanggal spesifik ini
                                                $attn = $student->attendances->firstWhere('date', $date);
                                                $status = $attn ? $attn->status : '-';
                                            @endphp

                                            <td class="px-2 py-3 text-center border-r border-gray-50">
                                                @if($status == 'Hadir')
                                                    <span class="inline-block w-6 h-6 leading-6 rounded-full bg-green-100 text-green-700 font-bold text-xs" title="Hadir">H</span>
                                                @elseif($status == 'Sakit')
                                                    <span class="inline-block w-6 h-6 leading-6 rounded-full bg-blue-100 text-blue-700 font-bold text-xs" title="Sakit">S</span>
                                                @elseif($status == 'Izin')
                                                    <span class="inline-block w-6 h-6 leading-6 rounded-full bg-orange-100 text-orange-700 font-bold text-xs" title="Izin">I</span>
                                                @elseif($status == 'Alpha')
                                                    <span class="inline-block w-6 h-6 leading-6 rounded-full bg-red-100 text-red-700 font-bold text-xs" title="Alpha">A</span>
                                                @else
                                                    <span class="text-gray-300 text-xs">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($dates) + 2 }}" class="px-6 py-12 text-center text-gray-400">
                                            Tidak ada data siswa atau presensi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const classSelect = document.getElementById('classroom_select');
        const subjectSelect = document.getElementById('subject_select');

        // Event ketika Kelas dipilih/diubah
        classSelect.addEventListener('change', function () {
            const classroomId = this.value;

            // Reset Dropdown Mapel
            subjectSelect.innerHTML = '<option value="">Sedang memuat...</option>';
            subjectSelect.disabled = true;
            subjectSelect.classList.add('bg-gray-50');

            if (classroomId) {
                // Panggil API Laravel
                fetch(`/admin/api/subjects/${classroomId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Bersihkan opsi lama
                        subjectSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
                        
                        // Isi opsi baru dari JSON
                        if (data.length > 0) {
                            data.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = subject.name;
                                subjectSelect.appendChild(option);
                            });
                            
                            // Aktifkan Dropdown
                            subjectSelect.disabled = false;
                            subjectSelect.classList.remove('bg-gray-50');
                        } else {
                            subjectSelect.innerHTML = '<option value="">Tidak ada mapel untuk Anda di kelas ini</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        subjectSelect.innerHTML = '<option value="">Gagal memuat mapel</option>';
                    });
            } else {
                // Jika pilihan kelas dikosongkan lagi
                subjectSelect.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
                subjectSelect.disabled = true;
                subjectSelect.classList.add('bg-gray-50');
            }
        });
    });
</script>
</x-app-layout>