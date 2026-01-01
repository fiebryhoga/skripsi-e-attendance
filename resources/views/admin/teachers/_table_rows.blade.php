<tr class="group hover:bg-gray-50/80 transition-colors duration-200 border-b border-gray-100 last:border-0">
    
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center gap-4">
            <div class="relative">
                <img class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-md group-hover:scale-105 transition-transform duration-300" 
                     src="{{ $teacher->avatar ? Storage::url($teacher->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=eef2ff&color=4f46e5&bold=true' }}" 
                     alt="{{ $teacher->name }}">
            </div>

            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600 transition-colors">
                        {{ $teacher->name }}
                    </a>
                    
                    @if($teacher->roles->contains(\App\Enums\UserRole::ADMIN))
                        <span class="inline-flex items-center p-0.5 rounded-full text-blue-600 bg-blue-50 border border-blue-100" title="Administrator">
                            <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </span>
                    @endif
                </div>
                
                <div class="flex flex-col mt-1 space-y-0.5">
                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                        <svg class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" /><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" /></svg>
                        {{ $teacher->email }}
                    </div>
                    @if($teacher->nip)
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 font-mono">
                            <svg class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" /></svg>
                            {{ $teacher->nip }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </td>

    <td class="px-6 py-4">
        <div class="flex flex-wrap gap-2 max-w-sm">
            @foreach($teacher->roles as $role)
                @php
                    $colors = match($role->value) {
                        'admin' => 'bg-violet-100 text-violet-700 ring-violet-500/30',
                        'guru_tatib' => 'bg-rose-100 text-rose-700 ring-rose-500/30',
                        'wali_kelas' => 'bg-sky-100 text-sky-700 ring-sky-500/30',
                        'guru_piket' => 'bg-amber-100 text-amber-700 ring-amber-500/30',
                        default => 'bg-gray-100 text-gray-600 ring-gray-500/20'
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $colors }}">
                    {{ $role->label() }}
                </span>
            @endforeach
            
            @if($teacher->roles->isEmpty())
                 <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium text-gray-400 bg-gray-50 ring-1 ring-inset ring-gray-200">
                    Pengajar Biasa
                </span>
            @endif
        </div>
    </td>

    <td class="px-6 py-4 text-right whitespace-nowrap">
        <div class="flex items-center justify-end gap-2">
            
            @if(!empty($teacher->phone))
                @php
                    $wa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $teacher->phone));
                @endphp
                <a href="https://wa.me/{{ $wa }}" target="_blank" 
                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 hover:text-green-700 hover:scale-110 transition-all duration-200 shadow-sm"
                   title="Chat WhatsApp">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </a>
            @endif

            <a href="{{ route('admin.teachers.edit', $teacher) }}" 
               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 hover:scale-110 transition-all duration-200 shadow-sm" 
               title="Edit Detail">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
            </a>

            <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ $teacher->name }}?');">
                @csrf @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 hover:scale-110 transition-all duration-200 shadow-sm" 
                        title="Hapus Data">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                </button>
            </form>
        </div>
    </td>
</tr>