<x-app-layout>
    <x-slot name="header">
        Daftar Pelanggaran - Kelas {{ $classroom->name }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            
            <div class="mb-2">
                <a href="{{ route('admin.homeroom.index', ['classroom_id' => $classroom->id]) }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg">Riwayat Pelanggaran Lengkap</h3>
                    <p class="text-sm text-gray-500">Total data: {{ $violations->total() }} pelanggaran</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 border-b border-gray-100 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Nama Siswa</th>
                                <th class="px-6 py-3">Jenis Pelanggaran</th>
                                <th class="px-6 py-3">Catatan</th>
                                <th class="px-6 py-3">Pelapor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($violations as $violation)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($violation->tanggal)->translatedFormat('d M Y') }}</span>
                                        <div class="text-[10px] text-gray-400">{{ $violation->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.homeroom.student.show', $violation->student_id) }}" class="font-bold text-gray-800 hover:text-indigo-600 hover:underline">
                                            {{ $violation->student->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                            {{ $violation->category->kode }}
                                        </span>
                                        <div class="mt-1 text-xs text-gray-600">{{ $violation->category->deskripsi }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 italic">
                                        {{ $violation->catatan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $violation->reporter->name ?? 'System' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        Tidak ada data pelanggaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($violations->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $violations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>