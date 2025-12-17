<x-app-layout>
    <x-slot name="header">Monitoring Wali Kelas</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- --- FITUR BARU: SWITCH CLASSROOM (TAB) --- --}}
            @if($allClassrooms->count() > 1)
                <div class="flex space-x-2 overflow-x-auto pb-2">
                    @foreach($allClassrooms as $c)
                        <a href="{{ route('admin.homeroom.index', ['classroom_id' => $c->id]) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors {{ $classroom->id == $c->id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                            Kelas {{ $c->name }}
                        </a>
                    @endforeach
                </div>
            @endif
            {{-- ------------------------------------------ --}}

            {{-- HEADER INFO KELAS --}}
            <div class="bg-indigo-600 rounded-2xl p-6 shadow-lg text-white flex justify-between items-center relative overflow-hidden">
                {{-- Hiasan Background --}}
                <div class="absolute right-0 top-0 h-full w-1/2 bg-white/5 skew-x-12 translate-x-12"></div>
                
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold">Kelas {{ $classroom->name }}</h2>
                    <p class="text-indigo-100 text-sm mt-1">Wali Kelas: {{ Auth::user()->name }}</p>
                </div>
                <div class="text-right relative z-10">
                    <p class="text-4xl font-bold">{{ $students->count() }}</p>
                    <p class="text-xs uppercase opacity-80">Total Siswa</p>
                </div>
            </div>

            {{-- BAGIAN 1: MONITORING HARI INI --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Ketidakhadiran Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('d F Y') }})
                </h3>

                @if($todayAbsences->isEmpty())
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Alhamdulillah, hari ini semua siswa terpantau HADIR di semua mata pelajaran (sejauh ini).
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-3">Nama Siswa</th>
                                    <th class="px-4 py-3">Mapel</th>
                                    <th class="px-4 py-3">Jam</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Keterangan</th>
                                    <th class="px-4 py-3">Guru Pengajar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($todayAbsences as $absense)
                                    <tr>
                                        <td class="px-4 py-3 font-bold text-gray-800">{{ $absense->student->name }}</td>
                                        <td class="px-4 py-3 text-indigo-600 font-medium">{{ $absense->schedule->subject->name }}</td>
                                        <td class="px-4 py-3 text-gray-500 text-xs">Jam {{ $absense->schedule->jam_mulai }}-{{ $absense->schedule->jam_selesai }}</td>
                                        <td class="px-4 py-3">
                                            @if($absense->status == 'Sakit')
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold">Sakit</span>
                                            @elseif($absense->status == 'Izin')
                                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-bold">Izin</span>
                                            @elseif($absense->status == 'Alpha')
                                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">Alpha</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 italic">{{ $absense->note ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $absense->schedule->teacher->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- 2. PELANGGARAN TERBARU --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        Pelanggaran Terbaru
                    </h3>

                    @if($latestViolations->isEmpty())
                        <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm font-medium text-center">
                            Kelas Aman. Belum ada catatan pelanggaran.
                        </div>
                    @else
                        <ul class="space-y-4">
                            @foreach($latestViolations as $violation)
                                <li class="flex gap-3 items-start text-sm pb-4 border-b border-gray-50 last:border-0">
                                    
                                    {{-- KOLOM KIRI: FOTO BUKTI (Thumbnail) --}}
                                    <div class="flex-shrink-0">
                                        @if($violation->bukti_foto)
                                            {{-- Tombol Zoom --}}
                                            <button onclick="openPhotoModal('{{ Storage::url($violation->bukti_foto) }}', '{{ $violation->student->name }}')" 
                                                    class="group relative w-12 h-12 rounded-lg overflow-hidden border border-gray-200 shadow-sm block">
                                                <img src="{{ Storage::url($violation->bukti_foto) }}" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" 
                                                     alt="Bukti">
                                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                                </div>
                                            </button>
                                        @else
                                            {{-- Placeholder jika tidak ada foto --}}
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- KOLOM KANAN: DETAIL INFO --}}
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <span class="font-bold text-gray-800">{{ $violation->student->name }}</span>
                                            <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($violation->tanggal)->translatedFormat('d M Y') }}</span>
                                        </div>

                                        {{-- Jenis Pelanggaran (Menggunakan relasi 'category') --}}
                                        <div class="mt-1">
                                            <span class="text-red-600 text-xs font-bold uppercase tracking-wide border border-red-100 bg-red-50 px-1.5 py-0.5 rounded">
                                                {{ $violation->category->name ?? 'Pelanggaran Umum' }}
                                            </span>
                                        </div>

                                        {{-- Catatan --}}
                                        @if($violation->catatan)
                                            <p class="text-gray-500 italic text-xs mt-1 bg-gray-50 p-2 rounded-lg border border-gray-100">
                                                "{{ $violation->catatan }}"
                                            </p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.student-violations.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">Lihat Semua Pelanggaran &rarr;</a>
                        </div>
                    @endif
                </div>
            

            {{-- BAGIAN 2: REKAP BULAN INI --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-800 text-lg">Akumulasi Bulan Ini ({{ \Carbon\Carbon::now()->translatedFormat('F Y') }})</h3>
                    
                    {{-- Tombol Cepat ke Rekap Detail --}}
                    <a href="{{ route('admin.attendances.recap', [
                        'classroom_id' => $classroom->id, 
                        'start_date' => date('Y-m-01'), 
                        'end_date' => date('Y-m-t'),
                        // Kita biarkan mapel kosong biar guru milih sendiri kalau mau detail
                    ]) }}" class="text-indigo-600 text-sm font-bold hover:underline">
                        Lihat Rekap Matriks Lengkap &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 border-b border-gray-100 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-3 w-10">No</th>
                                <th class="px-4 py-3">Nama Siswa</th>
                                <th class="px-4 py-3 text-center text-blue-600">Total Sakit</th>
                                <th class="px-4 py-3 text-center text-orange-600">Total Izin</th>
                                <th class="px-4 py-3 text-center text-red-600">Total Alpha</th>
                                <th class="px-4 py-3 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($students as $student)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-center text-gray-400">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $student->name }}</td>
                                    <td class="px-4 py-3 text-center font-bold {{ $student->sakit_count > 0 ? 'text-blue-700' : 'text-gray-300' }}">
                                        {{ $student->sakit_count }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold {{ $student->izin_count > 0 ? 'text-orange-700' : 'text-gray-300' }}">
                                        {{ $student->izin_count }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold {{ $student->alpha_count > 2 ? 'text-red-600 bg-red-50 rounded animate-pulse' : ($student->alpha_count > 0 ? 'text-red-600' : 'text-gray-300') }}">
                                        {{ $student->alpha_count }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($student->alpha_count >= 3)
                                            <button class="px-3 py-1 bg-red-600 text-white text-xs rounded shadow hover:bg-red-700">
                                                Panggil Siswa
                                            </button>
                                        @else
                                            <span class="text-green-500 text-xs font-bold">Aman</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>



    {{-- TARUH KODE MODAL INI DI BAGIAN PALING BAWAH FILE (Sebelum </x-app-layout>) --}}
<div id="photoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/80 transition-opacity backdrop-blur-sm" onclick="closePhotoModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl">
            
            {{-- Header Modal --}}
            <div class="bg-white px-4 py-3 flex justify-between items-center border-b border-gray-100">
                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modalStudentName">Bukti Pelanggaran</h3>
                <button type="button" onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Body Modal (Gambar Full) --}}
            <div class="bg-black flex justify-center items-center p-2">
                <img id="modalImage" src="" alt="Bukti Pelanggaran" class="max-h-[80vh] max-w-full object-contain rounded">
            </div>
        </div>
    </div>
</div>

<script>
    function openPhotoModal(imageUrl, studentName) {
        const modal = document.getElementById('photoModal');
        const img = document.getElementById('modalImage');
        const title = document.getElementById('modalStudentName');

        img.src = imageUrl;
        title.innerText = 'Bukti Pelanggaran: ' + studentName;
        modal.classList.remove('hidden');
    }

    function closePhotoModal() {
        const modal = document.getElementById('photoModal');
        modal.classList.add('hidden');
        document.getElementById('modalImage').src = ''; // Clear src
    }
</script>
</x-app-layout>