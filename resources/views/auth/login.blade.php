@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-8">
    <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
        <h1 class="text-xl font-semibold text-slate-800">Login</h1>
        <p class="text-sm text-slate-500 mt-1">Masuk ke akun Anda untuk mengelola data utang.</p>

        @if($errors->any())
            <div class="mt-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.attempt') }}" method="POST" class="mt-5 space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full border border-slate-300 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full border border-slate-300 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300">
                Ingat saya
            </label>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-xl transition">
                Login
            </button>
        </form>

        <p class="text-sm text-slate-600 mt-4 text-center">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Daftar</a>
        </p>
    </div>
</div>
@endsection
