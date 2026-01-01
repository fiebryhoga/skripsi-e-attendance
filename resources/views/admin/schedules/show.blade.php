<x-app-layout>
    
    <div class="bg-gray-50/50 min-h-screen" 
         x-data="{ 
            showCreateModal: false, 
            showEditModal: false,
            editData: {
                id: null,
                day: '',
                jam_mulai: 1,
                jam_selesai: 1,
                subject_id: '',
                subject_name: '',
                user_id: '',
                user_name: '',
                actionUrl: ''
            }
         }">
         
        <div class="space-y-8">
            
            
            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                <div class="flex md:flex-row flex-col items-start md:items-center gap-4">
                    <a href="{{ route('admin.schedules.index') }}" class="w-auto p-2 bg-white border border-gray-200 rounded-full text-gray-500 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Jadwal Kelas {{ $classroom->name }}</h1>
                        <p class="text-sm text-gray-500">Kelola mata pelajaran dan jam mengajar guru.</p>
                    </div>
                </div>
                
                <button @click="showCreateModal = true" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Jadwal
                </button>
            </div>

            
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

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; @endphp

                @foreach($days as $day)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                        
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">{{ $day }}</h3>
                            <span class="text-xs font-medium text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">
                                {{ isset($schedules[$day]) ? $schedules[$day]->count() : 0 }} Mapel
                            </span>
                        </div>

                        
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
                                            
                                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                
                                            <button @click="
                                                showEditModal = true;
                                                editData.id = {{ $schedule->id }};
                                                editData.day = '{{ $schedule->day }}';
                                                editData.jam_mulai = {{ $schedule->jam_mulai }};
                                                editData.jam_selesai = {{ $schedule->jam_selesai }};
                                                
                                                // ISI DATA ID & NAMA
                                                editData.subject_id = '{{ $schedule->subject_id }}';
                                                editData.subject_name = '{{ addslashes($schedule->subject->name) }} ({{ $schedule->subject->kode }})'; // <--- ISI NAMA
                                                
                                                editData.user_id = '{{ $schedule->user_id }}';
                                                editData.user_name = '{{ addslashes($schedule->teacher->name) }}'; // <--- ISI NAMA
                                                
                                                editData.actionUrl = '{{ route('admin.schedules.update', $schedule->id) }}';
                                            " class="text-gray-300 hover:text-indigo-600 p-1">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </button>

                                                
                                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-300 hover:text-red-500 p-1">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
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

            
            
            
            
            
            
            <div x-show="showCreateModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4"
                 x-transition.opacity>
                
                <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" @click.away="showCreateModal = false">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center flex-shrink-0">
                        <h3 class="font-bold text-lg text-gray-800">Tambah Jadwal Baru</h3>
                        <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>
                    
                    <form action="{{ route('admin.schedules.store', $classroom->id) }}" method="POST" class="p-6 space-y-5 overflow-y-auto">
                        @csrf
                        
                        
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
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam Mulai</label>
                                <select name="jam_mulai" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @for($i=1; $i<=15; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam Selesai</label>
                                <select name="jam_selesai" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @for($i=1; $i<=15; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                </select>
                            </div>
                        </div>

                        
                        
                        
                        <div x-data="{
                            open: false,
                            search: '',
                            selectedId: '',
                            selectedName: '-- Pilih Mapel --',
                            items: {{ Js::from($subjects->map(fn($s) => ['id' => $s->id, 'name' => $s->name . ' (' . $s->kode . ')'])) }},
                            get filteredItems() {
                                if (this.search === '') return this.items;
                                return this.items.filter(item => item.name.toLowerCase().includes(this.search.toLowerCase()));
                            }
                        }" class="relative">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Mata Pelajaran</label>
                            
                            <input type="hidden" name="subject_id" x-model="selectedId" required>

                            <button type="button" @click="open = !open; $nextTick(() => $refs.searchInput.focus())" 
                                    class="w-full bg-white border border-gray-300 text-gray-700 py-2.5 px-3 rounded-xl text-left focus:ring-indigo-500 focus:border-indigo-500 flex justify-between items-center">
                                <span x-text="selectedName" :class="{'text-gray-500': selectedId === ''}"></span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" @click.away="open = false" 
                                 class="absolute z-50 mt-1 w-full bg-white shadow-xl max-h-60 rounded-xl border border-gray-100 overflow-hidden flex flex-col"
                                 style="display: none;">
                                
                                <div class="p-2 border-b border-gray-100 bg-gray-50">
                                    <input x-ref="searchInput" x-model="search" type="text" placeholder="Cari mapel..." 
                                           class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <ul class="overflow-y-auto flex-1 p-1">
                                    <template x-for="item in filteredItems" :key="item.id">
                                        <li @click="selectedId = item.id; selectedName = item.name; open = false; search = ''"
                                            class="px-3 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg cursor-pointer transition-colors"
                                            x-text="item.name">
                                        </li>
                                    </template>
                                    <li x-show="filteredItems.length === 0" class="px-3 py-2 text-sm text-gray-400 text-center italic">
                                        Tidak ditemukan
                                    </li>
                                </ul>
                            </div>
                        </div>

                        
                        
                        
                        <div x-data="{
                            open: false,
                            search: '',
                            selectedId: '',
                            selectedName: '-- Cari Guru --',
                            items: {{ Js::from($teachers->map(fn($t) => ['id' => $t->id, 'name' => $t->name])) }},
                            get filteredItems() {
                                if (this.search === '') return this.items;
                                return this.items.filter(item => item.name.toLowerCase().includes(this.search.toLowerCase()));
                            }
                        }" class="relative">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Guru Pengajar</label>
                            
                            <input type="hidden" name="user_id" x-model="selectedId" required>

                            <button type="button" @click="open = !open; $nextTick(() => $refs.searchInput.focus())" 
                                    class="w-full bg-white border border-gray-300 text-gray-700 py-2.5 px-3 rounded-xl text-left focus:ring-indigo-500 focus:border-indigo-500 flex justify-between items-center">
                                <span x-text="selectedName" :class="{'text-gray-500': selectedId === ''}"></span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" @click.away="open = false" 
                                 class="absolute z-50 mt-1 w-full bg-white shadow-xl max-h-60 rounded-xl border border-gray-100 overflow-hidden flex flex-col"
                                 style="display: none;">
                                
                                <div class="p-2 border-b border-gray-100 bg-gray-50">
                                    <input x-ref="searchInput" x-model="search" type="text" placeholder="Cari nama guru..." 
                                           class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <ul class="overflow-y-auto flex-1 p-1">
                                    <template x-for="item in filteredItems" :key="item.id">
                                        <li @click="selectedId = item.id; selectedName = item.name; open = false; search = ''"
                                            class="px-3 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg cursor-pointer transition-colors"
                                            x-text="item.name">
                                        </li>
                                    </template>
                                    <li x-show="filteredItems.length === 0" class="px-3 py-2 text-sm text-gray-400 text-center italic">
                                        Guru tidak ditemukan
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showCreateModal = false" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30">Simpan Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>

            
            
            
            <div x-show="showEditModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4"
                 x-transition.opacity>
                
                <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" @click.away="showEditModal = false">
                    <div class="px-6 py-4 border-b border-gray-100 bg-yellow-50 flex justify-between items-center flex-shrink-0">
                        <h3 class="font-bold text-lg text-yellow-800">Edit Jadwal Pelajaran</h3>
                        <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>
                    
                    <form x-bind:action="editData.actionUrl" method="POST" class="p-6 space-y-5 overflow-y-auto">
                        @csrf
                        @method('PUT')
                        
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hari</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $d)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="day" value="{{ $d }}" x-model="editData.day" class="peer sr-only" required>
                                        <div class="text-center py-2 px-3 rounded-lg border border-gray-200 text-sm font-medium text-gray-600 peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500 transition-all hover:bg-gray-50">
                                            {{ $d }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam Mulai</label>
                                <select name="jam_mulai" x-model="editData.jam_mulai" class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring-yellow-500" required>
                                    @for($i=1; $i<=15; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam Selesai</label>
                                <select name="jam_selesai" x-model="editData.jam_selesai" class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring-yellow-500" required>
                                    @for($i=1; $i<=15; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                </select>
                            </div>
                        </div>

                        
                        
                        
                        <div x-data="{
                            open: false,
                            search: '',
                            items: {{ Js::from($subjects->map(fn($s) => ['id' => $s->id, 'name' => $s->name . ' (' . $s->kode . ')'])) }},
                            get filteredItems() {
                                if (this.search === '') return this.items;
                                return this.items.filter(item => item.name.toLowerCase().includes(this.search.toLowerCase()));
                            }
                        }" class="relative">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Mata Pelajaran</label>
                            
                            <input type="hidden" name="subject_id" x-model="editData.subject_id" required>

                            <button type="button" @click="open = !open; $nextTick(() => $refs.searchInput.focus())" 
                                    class="w-full bg-white border border-gray-300 text-gray-700 py-2.5 px-3 rounded-xl text-left focus:ring-yellow-500 focus:border-yellow-500 flex justify-between items-center">
                                <span x-text="editData.subject_name"></span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" @click.away="open = false" 
                                 class="absolute z-50 mt-1 w-full bg-white shadow-xl max-h-60 rounded-xl border border-gray-100 overflow-hidden flex flex-col"
                                 style="display: none;">
                                <div class="p-2 border-b border-gray-100 bg-gray-50">
                                    <input x-ref="searchInput" x-model="search" type="text" placeholder="Cari mapel..." 
                                           class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-500 focus:border-yellow-500">
                                </div>
                                <ul class="overflow-y-auto flex-1 p-1">
                                    <template x-for="item in filteredItems" :key="item.id">
                                        <li @click="editData.subject_id = item.id; editData.subject_name = item.name; open = false; search = ''"
                                            class="px-3 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg cursor-pointer transition-colors"
                                            x-text="item.name">
                                        </li>
                                    </template>
                                    <li x-show="filteredItems.length === 0" class="px-3 py-2 text-sm text-gray-400 text-center italic">Tidak ditemukan</li>
                                </ul>
                            </div>
                        </div>

                        
                        
                        
                        <div x-data="{
                            open: false,
                            search: '',
                            items: {{ Js::from($teachers->map(fn($t) => ['id' => $t->id, 'name' => $t->name])) }},
                            get filteredItems() {
                                if (this.search === '') return this.items;
                                return this.items.filter(item => item.name.toLowerCase().includes(this.search.toLowerCase()));
                            }
                        }" class="relative">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Guru Pengajar</label>
                            
                            <input type="hidden" name="user_id" x-model="editData.user_id" required>

                            <button type="button" @click="open = !open; $nextTick(() => $refs.searchInput.focus())" 
                                    class="w-full bg-white border border-gray-300 text-gray-700 py-2.5 px-3 rounded-xl text-left focus:ring-yellow-500 focus:border-yellow-500 flex justify-between items-center">
                                <span x-text="editData.user_name"></span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" @click.away="open = false" 
                                 class="absolute z-50 mt-1 w-full bg-white shadow-xl max-h-60 rounded-xl border border-gray-100 overflow-hidden flex flex-col"
                                 style="display: none;">
                                <div class="p-2 border-b border-gray-100 bg-gray-50">
                                    <input x-ref="searchInput" x-model="search" type="text" placeholder="Cari nama guru..." 
                                           class="w-full text-sm border-gray-200 rounded-lg focus:ring-yellow-500 focus:border-yellow-500">
                                </div>
                                <ul class="overflow-y-auto flex-1 p-1">
                                    <template x-for="item in filteredItems" :key="item.id">
                                        <li @click="editData.user_id = item.id; editData.user_name = item.name; open = false; search = ''"
                                            class="px-3 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg cursor-pointer transition-colors"
                                            x-text="item.name">
                                        </li>
                                    </template>
                                    <li x-show="filteredItems.length === 0" class="px-3 py-2 text-sm text-gray-400 text-center italic">Guru tidak ditemukan</li>
                                </ul>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showEditModal = false" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 bg-yellow-500 text-white font-bold rounded-xl hover:bg-yellow-600 shadow-lg shadow-yellow-500/30">Update Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>