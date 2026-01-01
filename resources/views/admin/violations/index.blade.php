<x-app-layout>
    <div class="bg-gray-50/50 min-h-screen">
        <div class="space-y-6">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Catatan Pelanggaran</h1>
                    <p class="text-sm text-gray-500 mt-1">Monitoring kedisiplinan dan riwayat perilaku siswa.</p>
                </div>
                
                <a href="{{ route('admin.student-violations.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white font-bold rounded-xl hover:from-orange-600 hover:to-red-700 shadow-lg shadow-orange-500/30 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Catat Pelanggaran
                </a>
            </div>
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                     class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            
            <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 max-w-lg">
                <form method="GET" class="relative flex items-center w-full">
                    <svg class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-10 pr-4 py-2 bg-transparent border-none focus:ring-0 text-sm text-gray-700 placeholder-gray-400" 
                           placeholder="Cari nama siswa atau kelas..." autocomplete="off">
                    @if(request('search'))
                        <a href="{{ route('admin.student-violations.index') }}" class="pr-3 text-gray-400 hover:text-red-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </a>
                    @else
                        <button type="submit" class="pr-3 text-gray-400 hover:text-indigo-600 font-medium text-xs">CARI</button>
                    @endif
                </form>
            </div>

            
            @include('admin.violations._table_rows')

        </div>
    </div>

    
    <div id="photoModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/90 backdrop-blur-sm transition-opacity" onclick="closePhotoModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                    
                    
                    <div class="bg-white px-4 py-3 flex justify-between items-center border-b border-gray-100">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Bukti Pelanggaran
                        </h3>
                        <button type="button" onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-1 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    
                    <div class="bg-black/5 p-4 flex justify-center items-center min-h-[300px]">
                        <img id="modalImage" src="" class="max-h-[75vh] max-w-full object-contain rounded-lg shadow-md">
                    </div>
                    
                    
                    <div class="bg-white px-4 py-3 border-t border-gray-100">
                        <p id="modalStudentName" class="text-sm font-semibold text-gray-800"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPhotoModal(url, studentName) {
            const modal = document.getElementById('photoModal');
            document.getElementById('modalImage').src = url;
            document.getElementById('modalStudentName').textContent = 'Siswa: ' + studentName;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }

        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            modal.classList.add('hidden');
            document.getElementById('modalImage').src = '';
            document.body.style.overflow = 'auto'; // Restore scrolling
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closePhotoModal();
        });
    </script>
</x-app-layout>