<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Manajemen Utang</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-slate-100 via-indigo-50 to-slate-200 min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-6">

        <div class="max-w-4xl w-full">

            <div class="bg-white/90 backdrop-blur-xl shadow-2xl rounded-[40px] overflow-hidden border border-slate-200">

                <div class="grid lg:grid-cols-2">

                    {{-- LEFT --}}
                    <div class="p-10 lg:p-14 flex flex-col justify-center">

                        <div class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full text-sm font-semibold w-fit mb-6">
                            💰 Financial Manager
                        </div>

                        <h1 class="text-5xl font-black leading-tight text-slate-800">
                            Web
                            <span class="text-indigo-600">
                                Manajemen
                            </span>
                            Utang
                        </h1>

                        <p class="mt-6 text-lg text-slate-500 leading-relaxed">
                            Kelola utang, pantau cicilan bulanan,
                            cek bulan yang sudah dibayar,
                            dan lihat sisa uang setelah pembayaran
                            secara otomatis.
                        </p>

                        <div class="mt-10 flex flex-col sm:flex-row gap-4">

                            {{-- BUTTON MENU --}}
                            <a href="/debts"
                               class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-lg shadow-indigo-200 transition duration-300 text-center">
                                Masuk ke Menu
                            </a>

                            {{-- BUTTON TAMBAH UTANG --}}
                            <a href="/debts/create"
                               class="px-8 py-4 bg-white border border-slate-300 hover:border-indigo-400 hover:text-indigo-600 rounded-2xl font-bold text-lg transition duration-300 text-center">
                                Tambah Utang
                            </a>

                        </div>

                        {{-- FEATURE --}}
                        <div class="mt-10 grid grid-cols-2 gap-4">

                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                <p class="text-3xl mb-2">📊</p>
                                <h3 class="font-bold text-slate-700">
                                    Rekap Otomatis
                                </h3>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                <p class="text-3xl mb-2">💸</p>
                                <h3 class="font-bold text-slate-700">
                                    Tracking Cicilan
                                </h3>
                            </div>

                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="relative bg-gradient-to-br from-indigo-600 via-violet-600 to-slate-900 p-10 lg:p-14 text-white overflow-hidden">

                        <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

                        <div class="relative z-10">

                            <div class="bg-white/10 border border-white/20 rounded-3xl p-6 backdrop-blur-xl mb-6">

                                <p class="text-sm uppercase tracking-widest text-indigo-200">
                                    Total Cicilan
                                </p>

                                <h2 class="mt-3 text-5xl font-black">
                                    Rp 2.500.000
                                </h2>

                                <p class="mt-2 text-indigo-100">
                                    Per bulan
                                </p>

                            </div>

                            <div class="space-y-4">

                                <div class="bg-white/10 rounded-2xl p-5 border border-white/10">
                                    <div class="flex justify-between">
                                        <span>Motor</span>
                                        <span>Bulan 5/12</span>
                                    </div>

                                    <div class="w-full h-3 bg-white/20 rounded-full mt-3 overflow-hidden">
                                        <div class="w-1/2 h-full bg-green-400 rounded-full"></div>
                                    </div>
                                </div>

                                <div class="bg-white/10 rounded-2xl p-5 border border-white/10">
                                    <div class="flex justify-between">
                                        <span>Laptop</span>
                                        <span>Bulan 2/6</span>
                                    </div>

                                    <div class="w-full h-3 bg-white/20 rounded-full mt-3 overflow-hidden">
                                        <div class="w-1/3 h-full bg-yellow-400 rounded-full"></div>
                                    </div>
                                </div>

                                <div class="bg-white/10 rounded-2xl p-5 border border-white/10">
                                    <div class="flex justify-between">
                                        <span>HP</span>
                                        <span>Lunas</span>
                                    </div>

                                    <div class="w-full h-3 bg-white/20 rounded-full mt-3 overflow-hidden">
                                        <div class="w-full h-full bg-green-400 rounded-full"></div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>