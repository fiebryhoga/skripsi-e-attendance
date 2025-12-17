<x-app-layout>
    <div class="py-8 bg-gray-50/50 min-h-screen" x-data="{ showModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Header Navigasi --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.schedules.index') }}" class="p-2 bg-white border border-gray-200 rounded-full text-gray-500 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Jadwal Kelas {{ $classroom->name }}</h1>
                        <p class="text-sm text-gray-500">Kelola mata pelajaran dan jam mengajar guru.</p>
                    </div>
                </div>
                
                <button @click="showModal = true" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Jadwal
                </button>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="p-4 bg-green-50 text-green-700 border border-green-200 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-50 text-red-700 border border-red-200 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="p-4 bg-red-50 text-red-700 border border-red-200 rounded-xl">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Grid Jadwal Hari --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                @endphp

                @foreach($days as $day)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                        {{-- Header Hari --}}
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">{{ $day }}</h3>
                            <span class="text-xs font-medium text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">
                                {{ isset($schedules[$day]) ? $schedules[$day]->count() : 0 }} Mapel
                            </span>
                        </div>

                        {{-- List Jadwal --}}
                        <div class="p-4 space-y-3 flex-1">
                            @if(isset($schedules[$day]))
                                @foreach($schedules[$day] as $schedule)
                                    <div class="group relative bg-white border border-gray-100 rounded-xl p-3 hover:shadow-md hover:border-indigo-100 transition-all">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                        Jam {{ $schedule->jam_mulai }} - {{ $schedule->jam_selesai }}
                                                    </span>
                                                </div>
                                                <h4 class="font-bold text-gray-800 text-sm">{{ $schedule->subject->name }}</h4>
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $schedule->teacher->name }}</p>
                                            </div>
                                            
                                            {{-- Tombol Hapus (Muncul saat hover) --}}
                                            <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-300 hover:text-red-500 p-1 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="h-full flex flex-col items-center justify-center text-gray-300 py-8 text-sm italic">
                                    Tidak ada jadwal
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- MODAL TAMBAH JADWAL --}}
            <div x-show="showModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4"
                 x-transition.opacity>
                
                <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden" @click.away="showModal = false">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-lg text-gray-800">Tambah Jadwal Pelajaran</h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>
                    
                    <form action="{{ route('admin.schedules.store', $classroom->id) }}" method="POST" class="p-6 space-y-5">
                        @csrf
                        
                        {{-- Pilih Hari --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hari</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $d)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="day" value="{{ $d }}" class="peer sr-only" required>
                                        <div class="text-center py-2 px-3 rounded-lg border border-gray-200 text-sm font-medium text-gray-600 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all hover:bg-gray-50">
                                            {{ $d }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Jam Mulai --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam Ke- (Mulai)</label>
                                <select name="jam_mulai" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @for($i=1; $i<=15; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            {{-- Jam Selesai --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam Ke- (Selesai)</label>
                                <select name="jam_selesai" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @for($i=1; $i<=15; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Pilih Mapel --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Mata Pelajaran</label>
                            <select name="subject_id" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                                <option value="">-- Pilih Mapel --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->kode }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pilih Guru --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Guru Pengajar</label>
                            
                            {{-- Menggunakan dropdown searchable sederhana dengan Alpine (opsional, atau select biasa) --}}
                            {{-- Disini saya pakai select biasa agar simpel dulu --}}
                            <select name="user_id" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                                <option value="">-- Cari Guru --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">* Hanya menampilkan guru (Wali Kelas / Guru Mapel).</p>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showModal = false" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30">Simpan Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>