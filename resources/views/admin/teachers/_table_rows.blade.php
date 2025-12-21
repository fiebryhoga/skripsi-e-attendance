<tr class="group hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100 last:border-0">
    
    {{-- KOLOM 1: PROFIL & KONTAK --}}
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center gap-4">
            {{-- Avatar --}}
            <div class="relative flex-shrink-0">
                <img class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm group-hover:ring-indigo-100 transition-all" 
                     src="{{ $teacher->avatar ? Storage::url($teacher->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=f3f4f6&color=4b5563' }}" 
                     alt="{{ $teacher->name }}">
                
                {{-- Indikator Admin (Bintang Kecil) --}}
                @if($teacher->roles->contains(\App\Enums\UserRole::ADMIN))
                    <div class="absolute -bottom-1 -right-1 bg-yellow-400 text-white p-0.5 rounded-full border-2 border-white" title="Administrator">
                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                @endif
            </div>

            {{-- Info Text --}}
            <div>
                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-sm font-semibold text-gray-900 hover:text-indigo-600 transition-colors block mb-0.5">
                    {{ $teacher->name }}
                </a>
                <div class="flex flex-col gap-0.5">
                    <span class="text-xs text-gray-500 font-mono flex items-center gap-1">
                        <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .6.4 1 1 1s1-.4 1-1m0 0v2.5" /></svg>
                        {{ $teacher->nip ?? '-' }}
                    </span>
                    <span class="text-[11px] text-gray-400 flex items-center gap-1">
                        <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $teacher->email }}
                    </span>
                </div>
            </div>
        </div>
    </td>

    {{-- KOLOM 2: JABATAN / ROLES --}}
    <td class="px-6 py-4">
        <div class="flex flex-wrap gap-1.5 max-w-xs">
            @foreach($teacher->roles as $role)
                @php
                    // Logika warna badge sederhana agar tidak monoton
                    $badgeColor = match($role->value) {
                        'admin' => 'bg-purple-50 text-purple-700 border-purple-100',
                        'guru_tatib' => 'bg-orange-50 text-orange-700 border-orange-100',
                        'wali_kelas' => 'bg-blue-50 text-blue-700 border-blue-100',
                        default => 'bg-gray-100 text-gray-600 border-gray-200'
                    };
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium border {{ $badgeColor }}">
                    {{ $role->label() }}
                </span>
            @endforeach
        </div>
    </td>

    {{-- KOLOM 3: KONTAK & AKSI --}}
    <td class="px-6 py-4 text-right whitespace-nowrap">
        <div class="flex items-center justify-end gap-2">
            
            {{-- Tombol WhatsApp (Jika ada HP) --}}
            @if(!empty($teacher->phone))
                @php
                    $wa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $teacher->phone));
                @endphp
                <a href="https://wa.me/{{ $wa }}" target="_blank" 
                   class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                   title="Chat WhatsApp: {{ $teacher->phone }}">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </a>
            @endif

            {{-- Tombol Edit --}}
            <a href="{{ route('admin.teachers.edit', $teacher) }}" 
               class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors group-hover:bg-white border border-transparent group-hover:border-gray-200" 
               title="Edit Detail">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
            </a>

            {{-- Tombol Hapus --}}
            <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ $teacher->name }}?');">
                @csrf @method('DELETE')
                <button type="submit" 
                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                        title="Hapus Data">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </form>
        </div>
    </td>
</tr>