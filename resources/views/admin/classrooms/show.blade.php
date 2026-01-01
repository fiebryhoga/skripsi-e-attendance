<x-app-layout>
    <x-slot name="header">Detail Kelas {{ $classroom->name }}</x-slot>

    
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl flex justify-between items-center shadow-sm">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </span>
            <button @click="show = false">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl shadow-sm">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        
        <div class="xl:col-span-1 space-y-6">
            
            
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden relative">
                <div class="h-24 bg-gradient-to-r from-indigo-600 to-violet-600"></div>
                <div class="px-6 pb-6 -mt-12 text-center">
                    <div class="w-24 h-24 mx-auto bg-white rounded-2xl flex items-center justify-center shadow-lg border-4 border-white">
                        <span class="text-3xl font-extrabold text-indigo-600">{{ $classroom->name }}</span>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-800">Manajemen Kelas</h3>
                    <p class="text-sm text-gray-500">Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}</p>
                    
                    <div class="mt-6 flex justify-center gap-4 text-center">
                        <div class="bg-indigo-50 px-4 py-2 rounded-xl">
                            <span class="block text-xl font-bold text-indigo-700">{{ $classroom->students->count() }}</span>
                            <span class="text-xs text-indigo-500 font-semibold uppercase">Total Siswa</span>
                        </div>
                        <div class="bg-green-50 px-4 py-2 rounded-xl">
                            <span class="block text-xl font-bold text-green-700">{{ $classroom->students->where('gender', 'L')->count() }}</span>
                            <span class="text-xs text-green-500 font-semibold uppercase">L</span>
                        </div>
                        <div class="bg-pink-50 px-4 py-2 rounded-xl">
                            <span class="block text-xl font-bold text-pink-700">{{ $classroom->students->where('gender', 'P')->count() }}</span>
                            <span class="text-xs text-pink-500 font-semibold uppercase">P</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 p-6 relative z-10">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Wali Kelas
                </h4>
                
                <form action="{{ route('admin.classrooms.update', $classroom) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @php
                        // Mapping Data Guru
                        $teacherList = $teachers->map(function($t) {
                            return [
                                'id' => $t->id,
                                'label' => $t->name,
                                'sublabel' => 'NIP: ' . $t->nip
                            ];
                        })->values(); // Reset index array
                    @endphp

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Cari Guru</label>
                        
                        <div x-data="searchableSelect({ 
                                items: {{ json_encode($teacherList) }}, 
                                selectedId: '{{ $classroom->teacher_id }}',
                                placeholder: 'Ketik Nama atau NIP Guru...'
                            })" class="relative">
                            
                            <input type="hidden" name="teacher_id" x-model="selectedId">

                            <div class="relative">
                                <input type="text" x-model="search" @click="isOpen = true" @click.away="isOpen = false" @keydown.escape="isOpen = false"
                                       :placeholder="selectedLabel ? selectedLabel : placeholder"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-3 pl-4 pr-10 transition-all cursor-pointer"
                                       readonly
                                       onfocus="this.removeAttribute('readonly');" 
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" @click="selectedId ? clear() : (isOpen = !isOpen)">
                                    <template x-if="selectedId">
                                        <svg class="w-5 h-5 text-red-400 hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </template>
                                    <template x-if="!selectedId">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </template>
                                </div>
                            </div>

                            <div x-show="isOpen" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 class="absolute z-50 w-full mt-1 bg-white rounded-xl shadow-xl border border-gray-100 max-h-60 overflow-y-auto">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <div @click="select(item)" class="px-4 py-3 hover:bg-indigo-50 cursor-pointer border-b border-gray-50 last:border-0">
                                        <div class="font-bold text-gray-800 text-sm" x-text="item.label"></div>
                                        <div class="text-xs text-gray-500" x-text="item.sublabel"></div>
                                    </div>
                                </template>
                                <div x-show="filteredItems.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">
                                    Tidak ditemukan.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-gray-900 text-white rounded-xl font-bold shadow-lg shadow-gray-900/20 hover:bg-black transition-all">
                        Simpan Wali Kelas
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Kelas</label>
                    <div class="w-full bg-gray-100 border border-gray-200 text-gray-500 rounded-xl px-4 py-3 font-mono font-medium">
                        {{ $classroom->name }}
                    </div>
                </div>
            </div>
        </div>

        
        <div class="xl:col-span-2" x-data="{ showAddModal: false, showTransferModal: false, selectedStudent: null }">
            
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden min-h-[600px] flex flex-col z-0">
                
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/30">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Anggota Kelas</h3>
                    
                    <button @click="showAddModal = true" class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Masukan / Pindahkan Siswa
                    </button>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-4">Siswa</th>
                                <th class="px-6 py-4">NIS</th>
                                <th class="px-6 py-4">L/P</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($classroom->students as $student)
                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-3">
                                    <img class="w-8 h-8 rounded-full object-cover" src="{{ $student->photo ? Storage::url($student->photo) : 'https://ui-avatars.com/api/?name='.$student->name }}" alt="">
                                    {{ $student->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $student->nis }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold {{ $student->gender == 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                                        {{ $student->gender }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="showTransferModal = true; selectedStudent = {{ $student }}" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Pindah</button>
                                        
                                        <form action="{{ route('admin.students.release', $student) }}" method="POST" onsubmit="return confirm('Keluarkan {{ $student->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Keluarkan</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    <p>Kelas ini belum memiliki siswa.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            
            <div x-show="showAddModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4"
                 x-transition.opacity>
                
                <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6" @click.away="showAddModal = false">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Masukan Siswa ke Kelas</h3>
                    <p class="text-sm text-gray-500 mb-4">Cari siswa baru atau siswa dari kelas lain.</p>
                    
                    <form action="{{ route('admin.classrooms.assign-student', $classroom) }}" method="POST">
                        @csrf
                        
                        @php
                            // Mapping Data Siswa Available (Siswa yang belum punya kelas ATAU punya kelas lain)
                            $studentList = $availableStudents->map(function($s) {
                                // Tentukan Status & Warna
                                if ($s->classroom) {
                                    $statusLabel = "Pindahan dari {$s->classroom->name}";
                                    $statusColor = "text-orange-600 font-bold";
                                } else {
                                    $statusLabel = "Belum Masuk Kelas";
                                    $statusColor = "text-green-600 font-medium";
                                }

                                return [
                                    'id' => $s->id,
                                    'label' => "{$s->name} ({$s->nis})",
                                    'sublabel' => $statusLabel,
                                    'status_color' => $statusColor
                                ];
                            })->values(); // Reset index array agar JSON aman
                        @endphp

                        <div class="mb-6">
                             <div x-data="searchableSelect({ 
                                    items: {{ json_encode($studentList) }}, 
                                    selectedId: '',
                                    placeholder: 'Cari Nama atau NIS...'
                                })" class="relative">
                                
                                <input type="hidden" name="student_id" x-model="selectedId" required>

                                <div class="relative">
                                    <input type="text" x-model="search" @click="isOpen = true" @click.away="isOpen = false"
                                           :placeholder="selectedLabel ? selectedLabel : placeholder"
                                           class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 pl-4 pr-10 transition-all cursor-pointer"
                                           readonly onfocus="this.removeAttribute('readonly');">
                                           
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" @click="selectedId ? clear() : (isOpen = !isOpen)">
                                        <template x-if="selectedId"><svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></template>
                                        <template x-if="!selectedId"><svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg></template>
                                    </div>
                                </div>

                                <div x-show="isOpen" class="absolute z-50 w-full mt-1 bg-white rounded-xl shadow-xl border border-gray-100 max-h-60 overflow-y-auto">
                                    <template x-for="item in filteredItems" :key="item.id">
                                        <div @click="select(item)" class="px-4 py-3 hover:bg-indigo-50 cursor-pointer border-b border-gray-50 flex justify-between items-center">
                                            <div>
                                                <div class="font-bold text-gray-800 text-sm" x-text="item.label"></div>
                                                <div class="text-xs" :class="item.status_color" x-text="item.sublabel"></div>
                                            </div>
                                            
                                            <template x-if="item.sublabel.includes('Pindahan')">
                                                 <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                            </template>
                                        </div>
                                    </template>
                                    <div x-show="filteredItems.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">Data tidak ditemukan.</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="showAddModal = false" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            
            <div x-show="showTransferModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4"
                 x-transition.opacity>
                
                <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6" @click.away="showTransferModal = false">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Pindahkan Siswa</h3>
                    <p class="text-sm text-gray-500 mb-6">Pindahkan <span class="font-bold text-indigo-600" x-text="selectedStudent?.name"></span> ke kelas lain.</p>
                    
                    <form :action="`{{ url('admin/students') }}/${selectedStudent?.id}/transfer`" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Kelas Tujuan</label>
                            <select name="target_classroom_id" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 py-3" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($otherClassrooms as $oc)
                                    <option value="{{ $oc->id }}">{{ $oc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="showTransferModal = false" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700">Pindahkan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('searchableSelect', (config) => ({
                items: config.items || [],
                selectedId: config.selectedId || '',
                selectedLabel: '',
                search: '',
                isOpen: false,
                placeholder: config.placeholder || 'Select...',

                init() {
                    // Set label awal jika sudah ada ID terpilih (misal wali kelas lama)
                    if (this.selectedId) {
                        const found = this.items.find(i => i.id == this.selectedId);
                        if (found) {
                            this.selectedLabel = found.label;
                        }
                    }
                },

                get filteredItems() {
                    if (this.search === '') return this.items;
                    return this.items.filter(item => 
                        item.label.toLowerCase().includes(this.search.toLowerCase()) || 
                        item.sublabel.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                select(item) {
                    this.selectedId = item.id;
                    this.selectedLabel = item.label;
                    this.isOpen = false;
                    this.search = ''; // Reset search text
                },

                clear() {
                    this.selectedId = '';
                    this.selectedLabel = '';
                    this.search = '';
                }
            }))
        })
    </script>
</x-app-layout>