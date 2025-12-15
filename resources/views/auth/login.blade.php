<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - E-Attendance SMAN 1 Malang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">

    <div class="min-h-screen flex items-center justify-center p-4 lg:p-0">
        
        <div class="bg-white w-full h-screen lg:h-auto lg:max-w-7xl lg:rounded-3xl lg:shadow-2xl overflow-hidden flex flex-col lg:flex-row min-h-screen lg:min-h-[85vh]">
            
            <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 text-white flex-col justify-between p-12">
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop" 
                         alt="SMAN 1 Malang" 
                         class="w-full h-full object-cover opacity-40 mix-blend-overlay">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-blue-900 to-purple-900 opacity-90"></div>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="text-xl font-bold tracking-wide">E-Attendance</span>
                    </div>
                </div>

                <div class="relative z-10 space-y-4">
                    <h1 class="text-4xl font-bold leading-tight">Membangun Karakter <br>Disiplin & Berprestasi.</h1>
                    <p class="text-indigo-200 text-lg font-light">Sistem Informasi Manajemen Kedisiplinan <br>SMA Negeri 1 Malang.</p>
                </div>

                <div class="relative z-10 text-xs text-indigo-300">
                    &copy; {{ date('Y') }} SMAN 1 Malang. All rights reserved.
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 lg:px-20 py-12 relative bg-white">
                
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 rounded-full bg-indigo-50 blur-3xl opacity-50 pointer-events-none"></div>

                <div class="relative w-full max-w-md mx-auto">
                    <div class="lg:hidden mb-8 text-center">
                        <h2 class="text-2xl font-bold text-indigo-700">SMAN 1 Malang</h2>
                        <p class="text-gray-500 text-sm">E-Attendance System</p>
                    </div>

                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang 👋</h2>
                        <p class="text-gray-500">Silakan masukkan NIP dan Password Anda.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">Nomor Induk Pegawai (NIP)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                                <input id="nip" type="text" name="nip" :value="old('nip')" required autofocus autocomplete="username"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-800 shadow-sm"
                                    placeholder="Contoh: 19800101">
                            </div>
                            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-800 shadow-sm"
                                    placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4 cursor-pointer" name="remember">
                                <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                            </label>
                            
                            @if (Route::has('password.request'))
                                <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors" href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-500/30 transform transition-all duration-200 hover:-translate-y-0.5 hover:shadow-indigo-500/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Masuk Aplikasi
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-500">
                            Mengalami kendala login? <a href="#" class="text-indigo-600 font-medium hover:underline">Hubungi Admin</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>