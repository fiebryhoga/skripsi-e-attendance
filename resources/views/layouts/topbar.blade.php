<header class="bg-white/90 backdrop-blur-xl border-b border-gray-100 sticky top-0 z-40 transition-all duration-300">
    <div class="flex items-center justify-between h-20 px-6 lg:px-10">
        
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-indigo-600 lg:hidden transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <h1 class="text-xl font-bold text-gray-800 hidden md:block tracking-tight">
                {{ $header ?? 'Dashboard' }}
            </h1>
        </div>

        <div class="flex items-center gap-5">
            
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors rounded-full hover:bg-indigo-50">
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-2 right-2 h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white animate-pulse"></span>
                    @endif
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                    
                    <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700">Notifikasi</span>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <a href="{{ route('profile.edit') }}" class="text-[10px] text-indigo-600 hover:underline">Tandai sudah dibaca</a>
                        @endif
                    </div>

                    <div class="max-h-64 overflow-y-auto">
                        @forelse(Auth::user()->notifications as $notification)
                            <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer {{ $notification->read_at ? 'opacity-60' : '' }}">
                                <div class="flex items-start gap-3">
                                    <div class="p-1.5 rounded-full {{ $notification->data['type'] == 'danger' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                        @if($notification->data['type'] == 'danger')
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-800 font-medium">{{ $notification->data['message'] }}</p>
                                        <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center text-gray-400 text-xs">
                                Tidak ada notifikasi baru.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none group">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-md group-hover:shadow-lg transition-all" 
                         src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-400">{{ Auth::user()->role->label() }}</div>
                    </div>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-4 w-48 bg-white rounded-xl shadow-xl py-2 border border-gray-100 z-50 origin-top-right"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>