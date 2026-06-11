<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debt Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.x/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 min-h-screen">

    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-slate-200 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 md:px-6">
            <div class="h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-lg">💰</div>
                    <span class="text-base font-semibold text-slate-800">Debt Manager</span>
                </a>

                <button
                    type="button"
                    class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-600"
                    onclick="toggleMobileMenu()"
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                    id="mobile-menu-button"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="4" y1="6" x2="20" y2="6"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="18" x2="20" y2="18"></line>
                    </svg>
                </button>

                <div class="hidden md:flex items-center gap-2">
                    @auth
                        <a href="/" class="px-4 py-2 rounded-xl hover:bg-slate-100 text-slate-600 text-sm font-medium transition">Home</a>
                        <a href="/debts" class="px-4 py-2 rounded-xl hover:bg-slate-100 text-slate-600 text-sm font-medium transition">Semua Utang</a>
                        <a href="/debts/create" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition">+ Tambah Utang</a>
                        <span class="px-3 py-2 text-sm text-slate-500">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl hover:bg-slate-100 text-slate-600 text-sm font-medium transition">Logout</button>
                        </form>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl hover:bg-slate-100 text-slate-600 text-sm font-medium transition">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition">Register</a>
                    @endguest
                </div>
            </div>

            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <div class="grid gap-2">
                    @auth
                        <a href="/" class="px-4 py-2 rounded-xl bg-slate-50 text-slate-700 text-sm font-medium">Home</a>
                        <a href="/debts" class="px-4 py-2 rounded-xl bg-slate-50 text-slate-700 text-sm font-medium">Semua Utang</a>
                        <a href="/debts/create" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium">+ Tambah Utang</a>
                        <div class="px-4 py-2 rounded-xl bg-slate-50 text-sm text-slate-500">{{ auth()->user()->name }}</div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 rounded-xl bg-slate-50 text-slate-700 text-sm font-medium">Logout</button>
                        </form>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-slate-50 text-slate-700 text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium">Register</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6 md:py-8 px-4 md:px-6">
        @if(session('success'))
            <div class="max-w-5xl mx-auto mb-5">
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    <i class="ti ti-circle-check text-base"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');
            const isHidden = menu.classList.contains('hidden');

            menu.classList.toggle('hidden');
            button.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        }
    </script>

</body>
</html>