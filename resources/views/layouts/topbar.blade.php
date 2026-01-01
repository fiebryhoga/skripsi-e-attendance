<header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40 transition-all duration-300">
    <div class="flex items-center justify-between h-20 px-6 lg:px-10">
        
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-indigo-600 lg:hidden transition-colors p-1 rounded-lg hover:bg-gray-100">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <h1 class="text-xl font-bold text-gray-800 hidden md:block tracking-tight">
                {{ $header ?? 'Dashboard' }}
            </h1>
        </div>

        <div class="flex items-center gap-4 sm:gap-6">
            
            <div class="relative" 
                x-data="{ 
                    open: false, 
                    unreadCount: {{ Auth::user()->unreadNotifications->count() }},
                    markAsRead() {
                        if (this.unreadCount > 0) {
                            this.unreadCount = 0;
                            fetch('{{ route('notifications.markRead') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            });
                        }
                    }
                }">
                
                
                <button @click="open = !open; if(open) markAsRead()" class="relative p-2.5 text-gray-400 hover:text-indigo-600 transition-all rounded-full hover:bg-indigo-50 focus:outline-none">
                    <template x-if="unreadCount > 0">
                        <span class="absolute top-1.5 right-1.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white animate-pulse" x-text="unreadCount"></span>
                    </template>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>

                
                
                <div x-show="open" @click.away="open = false" style="display: none;"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    
                    
                    class="
                        /* MOBILE: Fixed position, memenuhi lebar layar dikurangi margin */
                        fixed inset-x-4 top-20 mt-2
                        
                        /* DESKTOP (sm ke atas): Balik ke Absolute position di kanan */
                        sm:absolute sm:inset-x-auto sm:top-full sm:right-0 sm:mt-3 sm:w-96
                        
                        bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden z-50
                    ">
                    
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-800">Notifikasi</span>
                        <span class="text-xs text-gray-400">{{ Auth::user()->notifications->count() }} Pesan</span>
                    </div>

                    
                    <div class="max-h-[60vh] overflow-y-auto">
                        @forelse(Auth::user()->notifications as $notification)
                            
                            <div class="px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer group {{ $notification->read_at ? 'opacity-60 bg-white' : 'bg-indigo-50/30' }}">
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="p-2 rounded-full {{ ($notification->data['type'] ?? '') == 'danger' ? 'bg-red-100 text-red-600' : 'bg-indigo-100 text-indigo-600' }}">
                                            @if(($notification->data['type'] ?? '') == 'danger')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-800 font-medium leading-snug group-hover:text-indigo-600 transition-colors">
                                            {{ $notification->data['message'] ?? 'Notifikasi Baru' }}
                                        </p>
                                        <p class="text-[11px] text-gray-400 mt-1.5 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-10 px-4 text-center">
                                <div class="bg-gray-50 p-3 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <p class="text-sm text-gray-500 font-medium">Belum ada notifikasi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none group p-1 pr-3 rounded-full hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                    <img class="h-9 w-9 rounded-full object-cover border-2 border-white shadow-sm group-hover:shadow-md transition-all" 
                         src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=6366f1&color=ffffff' }}" alt="Avatar">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-bold text-gray-700 group-hover:text-indigo-600 transition-colors leading-tight">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] font-medium text-gray-400 uppercase tracking-wide">{{ Auth::user()->roles->first()?->label() ?? 'User' }}</div>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-400 transition-transform duration-200 hidden md:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" style="display: none;"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                    class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-gray-100 z-50 origin-top-right overflow-hidden">
                    
                    <div class="px-4 py-3 border-b border-gray-50 md:hidden bg-gray-50">
                        <div class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors group">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Pengaturan Akun
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors group">
                                <svg class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>