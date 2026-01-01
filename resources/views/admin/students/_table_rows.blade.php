
<tbody>
    @forelse($students as $student)
    <tr class="hover:bg-indigo-50/30 transition-colors duration-200 group cursor-pointer border-b border-gray-50" 
        onclick="window.location='{{ route('admin.students.show', $student) }}'">
        
        
        <td class="px-8 py-4">
            <div class="flex items-center gap-4">
                
                <div class="relative flex-shrink-0"> 
                    <img class="w-10 h-10 rounded-full object-cover shadow-sm ring-2 ring-white" 
                        src="{{ $student->photo ? Storage::url($student->photo) : 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=C0C0C0&color=fff' }}"
                         alt="Foto">
                </div>
                
                
                <div class="min-w-0"> 
                    <h4 class="font-bold text-gray-800 group-hover:text-indigo-600 transition-colors text-sm truncate" title="{{ $student->name }}">
                        {{ Str::limit($student->name, 25, '...') }}
                    </h4>
                    <p class="text-xs text-gray-400">No. Ortu {{ $student->phone_parent ?? '-' }}</p>
                </div>
            </div>
        </td>

        
        <td class="px-6 py-4">
            <div class="flex flex-col gap-1">
                <span class="inline-block bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 w-fit">
                    NIS. {{ $student->nis }}
                </span>
                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wide">
                    Angkatan {{ $student->angkatan }}
                </span>
            </div>
        </td>

        
        <td class="px-6 py-4">
            @if($student->gender == 'L')
                <span class="inline-flex items-center gap-1 text-[10px] font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                    Laki-laki
                </span>
            @elseif($student->gender == 'P')
                <span class="inline-flex items-center gap-1 text-[10px] font-medium text-pink-600 bg-pink-50 px-2 py-1 rounded-full">
                    Perempuan
                </span>
            @else
                <span class="text-gray-400">-</span>
            @endif
        </td>

        
        <td class="px-6 py-4">
            @if($student->classroom)
                <div class="flex items-center gap-2">
                    
                    @php
                        $dotColor = 'bg-purple-500';
                        if(str_contains($student->classroom->name, 'X-')) $dotColor = 'bg-blue-500';
                        if(str_contains($student->classroom->name, 'XI-')) $dotColor = 'bg-indigo-500';
                    @endphp
                    
                    <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                    
                    <span class="text-sm text-gray-700 font-bold">
                        {{ $student->classroom->name }}
                    </span>
                </div>
                <div class="text-[10px] text-gray-400 pl-4 mt-0.5">
                    {{ $student->classroom->teacher ? Str::limit($student->classroom->teacher->name, 15) : 'Wali Kosong' }}
                </div>
            @else
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                    <span class="text-sm text-gray-400 font-medium italic">Belum masuk kelas</span> 
                </div>
            @endif
        </td>

        
        
        <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
            <div class="flex items-center justify-center gap-2">
                
                
                <a href="{{ route('admin.students.edit', $student) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50/20 text-amber-600 border border-amber-200/10 rounded-lg text-xs font-bold hover:bg-amber-100/40 hover:text-amber-700/40 transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    Edit
                </a>

                
                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" onsubmit="return confirm('Hapus siswa {{ $student->name }} selamanya?')">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 rounded-lg text-xs font-bold hover:bg-red-100 hover:text-red-700 transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Hapus
                    </button>
                </form>

            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-10 text-center text-gray-400">
            <div class="flex flex-col items-center justify-center">
                <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <span class="font-medium text-sm">Data siswa tidak ditemukan</span>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>



@if($students->hasPages())
    <tfoot>
        <tr>
            <td colspan="5" class="px-6 py-4 border-t border-gray-100 bg-red-50/50">
                {{ $students->links() }} 
            </td>
        </tr>
    </tfoot>
@endif