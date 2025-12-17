<x-app-layout>
    <div class="bg-gray-50/50 min-h-screen">
        <div class="space-y-8">
            
            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-2">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                        Pelanggaran Siswa
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Monitoring kedisiplinan dan catatan perilaku siswa.</p>
                </div>
                <a href="{{ route('admin.student-violations.create') }}" 
                   class="group inline-flex items-center px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-xl shadow-lg hover:bg-indigo-600 hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Catat Pelanggaran
                </a>
            </div>

            {{-- Alert --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm animate-fade-in-down">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Include Tabel Terpisah --}}
            @include('admin.violations._table_rows')

        </div>
    </div>
</x-app-layout>