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
        <div class="max-w-5xl mx-auto px-6">
            <div class="h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-lg">💰</div>
                    <span class="text-base font-semibold text-slate-800">Debt Manager</span>
                </a>
                <div class="hidden md:flex items-center gap-2">
                    <a href="/" class="px-4 py-2 rounded-xl hover:bg-slate-100 text-slate-600 text-sm font-medium transition">Home</a>
                    <a href="/debts" class="px-4 py-2 rounded-xl hover:bg-slate-100 text-slate-600 text-sm font-medium transition">Semua Utang</a>
                    <a href="/debts/create" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition">+ Tambah Utang</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-8 px-4 md:px-6">
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

</body>
</html>