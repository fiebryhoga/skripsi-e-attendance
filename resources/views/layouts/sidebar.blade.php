<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    
    <div class="flex flex-col items-center justify-center h-32 border-b border-gray-100 bg-gray-50/30">
        <img src="{{ asset('assets/images/layouts/logo.png') }}" 
             alt="Logo SMAN 1 Malang" 
             class="h-12 w-auto object-contain mb-3 hover:scale-105 transition-transform duration-300">
        
        <div class="text-center">
            <h1 class="text-xl font-extrabold text-indigo-900 tracking-tight leading-none">SIMADIS</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">SMAN 1 Malang</p>
        </div>
    </div>

    
    <div class="px-6 py-6 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="relative">
                <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm ring-1 ring-gray-100" 
                     src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=eef2ff&color=4f46e5' }}" 
                     alt="{{ Auth::user()->name }}" />
                <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-green-500"></span>
            </div>
            <div class="overflow-hidden">
                <p class="text-xs text-indigo-600 font-medium bg-indigo-50 inline-block px-2 py-0.5 rounded-full mt-1">
                    {{ Auth::user()->roles->first()?->label() ?? 'User' }}
                </p>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-4 space-y-1">
        
        
        
        
        <a href="{{ route('dashboard') }}" 
           class="{{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
            <svg class="{{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Dashboard
        </a>

        @if(Auth::user()->hasRole(\App\Enums\UserRole::ADMIN))
            <a href="{{ route('admin.wa.connect') }}" target="_blank" 
               class="text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <svg class="text-gray-400 group-hover:text-indigo-600 mr-3 flex-shrink-0 h-5 w-5 transition-colors duration-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.506-.669-.514-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.015-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.084 1.758-.719 2.006-1.413.248-.695.248-1.289.173-1.414z"/>
                </svg>
                WhatsApp Gateway
            </a>
        @endif
        
        @if(Auth::user()->hasRole(\App\Enums\UserRole::ADMIN))
            
            <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                Master Data
            </div>

            <a href="{{ route('admin.students.index') }}" 
            class="{{ request()->routeIs('admin.students.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <svg class="{{ request()->routeIs('admin.students.*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Data Siswa
            </a>

            <a href="{{ route('admin.teachers.index') }}" 
                class="{{ request()->routeIs('admin.teachers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <svg class="{{ request()->routeIs('admin.teachers.*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Data Guru & Staf
            </a>

            <a href="{{ route('admin.classrooms.index') }}" 
            class="{{ request()->routeIs('admin.classrooms.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <svg class="text-gray-400 group-hover:text-indigo-600 mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>    
                Data Kelas
            </a>

            <a href="{{ route('admin.subjects.index') }}" 
            class="{{ request()->routeIs('admin.subjects.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <svg class="{{ request()->routeIs('admin.subjects.*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Mata Pelajaran
            </a>

            <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                Akademik
            </div>

            <a href="{{ route('admin.schedules.index') }}" 
            class="{{ request()->routeIs('admin.schedules.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <svg class="{{ request()->routeIs('admin.schedules.*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Atur Jadwal Pelajaran
            </a>

        @endif 
        


        
        
        
        
        @if(Auth::user()->hasAnyRole([\App\Enums\UserRole::ADMIN, \App\Enums\UserRole::GURU_TATIB]))
            
            <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                Kedisiplinan
            </div>

            
            
                <a href="{{ route('admin.violations.index') }}"
                class="{{ request()->routeIs('admin.violations.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                    <svg class="{{ request()->routeIs('admin.violations.*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Kategori Pelanggaran
                </a>
            
            
            
            <a href="{{ route('admin.student-violations.index') }}" 
            class="{{ request()->routeIs('admin.student-violations.*') ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'text-gray-600 hover:bg-red-50 hover:text-red-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <svg class="{{ request()->routeIs('admin.student-violations.*') ? 'text-white' : 'text-gray-400 group-hover:text-red-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Pelanggaran Siswa
            </a>

        @endif


        
        
        
        
        
        @if(!Auth::user()->hasRole(\App\Enums\UserRole::ADMIN))
            <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                Akademik
            </div>
        @endif

        <a href="{{ route('admin.attendances.index') }}" 
           class="{{ (request()->routeIs('admin.attendances.*') && !request()->routeIs('admin.attendances.recap')) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
            <svg class="{{ (request()->routeIs('admin.attendances.*') && !request()->routeIs('admin.attendances.recap')) ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            Presensi Siswa
        </a>

        
        <a href="{{ route('admin.attendances.recap') }}" 
           class="{{ request()->routeIs('admin.attendances.recap') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
            <svg class="{{ request()->routeIs('admin.attendances.recap') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Rekap Presensi
        </a>


        
        
        
        @if(Auth::user()->hasRole(\App\Enums\UserRole::WALI_KELAS))
            
            <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                Wali Kelas
            </div>

            <a href="{{ route('admin.homeroom.index') }}" 
            class="{{ request()->routeIs('admin.homeroom.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                <svg class="{{ request()->routeIs('admin.homeroom.*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Monitoring Kelas Saya
            </a>
        @endif



        
        
        
        @if(Auth::user()->hasRole(\App\Enums\UserRole::ADMIN))
            
            <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                System
            </div>

            <a href="{{ route('admin.settings.index') }}"
            class="{{ request()->routeIs('admin.settings.*') ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'text-gray-600 hover:bg-red-50 hover:text-red-600' }} group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                
                <svg class="{{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-gray-400 group-hover:text-red-600' }} mr-3 flex-shrink-0 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Pengaturan & Reset
            </a>
        @endif

    </nav>

    
    <div class="p-4 border-t border-gray-100 bg-white">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-red-50 hover:text-red-600 rounded-xl border border-gray-200 transition-all duration-200 group">
                <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>