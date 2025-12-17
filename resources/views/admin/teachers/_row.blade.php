<tr onclick="window.location='{{ route('admin.teachers.edit', $teacher) }}'" 
    class="group cursor-pointer hover:bg-indigo-50/40 transition-colors duration-200">
    
    <td class="px-8 py-5">
        <div class="flex items-center gap-4">
            <div class="relative transition-transform duration-300 group-hover:scale-105">
                <img class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm" 
                     src="{{ $teacher->avatar ? Storage::url($teacher->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=C0C0C0' }}" 
                     alt="">
                
                {{-- Status Online/Aktif --}}
                <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 text-base group-hover:text-indigo-600 transition-colors">
                    {{ $teacher->name }}
                </h4>
                <div class="flex items-center gap-2 mt-0.5 text-xs">
                    <span class="font-mono text-gray-500 bg-white border border-gray-200 px-1.5 py-0.5 rounded">{{ $teacher->nip }}</span>
                    @if($teacher->roles->contains(\App\Enums\UserRole::ADMIN))
                        <span class="flex items-center gap-1 text-yellow-600 font-bold">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Admin
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </td>

    <td class="px-6 py-5">
        <div class="flex flex-wrap gap-2 max-w-sm">
            @foreach($teacher->roles as $role)
                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide border bg-gray-100 text-gray-600 border-gray-200">
                    {{ $role->label() }}
                </span>
            @endforeach
        </div>
    </td>

    <td class="px-6 py-5 text-right">
        <div class="flex items-center justify-end gap-3">
            
            <span class="text-xs font-bold text-gray-400 group-hover:text-indigo-500 transition-colors mr-2">
                Edit Detail
            </span>

            <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" onclick="event.stopPropagation()" onsubmit="return confirm('Hapus {{ $teacher->name }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus User">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>

            <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </td>
</tr>