<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | DRICS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 antialiased">

    <main class="grid min-h-screen lg:grid-cols-2">

        {{-- LEFT: BRANDING PANEL --}}
        <section class="relative hidden overflow-hidden bg-slate-950 text-white lg:flex lg:flex-col lg:justify-between lg:p-12 xl:p-16">

            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900 to-blue-950"></div>

            <div class="absolute -right-32 top-20 h-96 w-96 rounded-full border border-blue-400/10"></div>
            <div class="absolute -right-20 top-32 h-72 w-72 rounded-full border border-blue-400/10"></div>

            <div class="absolute -bottom-40 -left-20 h-96 w-96 rounded-full bg-blue-600/20 blur-3xl"></div>

            <div class="relative z-10 flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-600 shadow-lg shadow-blue-600/30">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-widest">DRICS</h1>
                    <p class="mt-1 text-xs tracking-[0.18em] text-slate-400">
                        POWER PLANT DIVISION
                    </p>
                </div>
            </div>

            <div class="relative z-10 max-w-xl py-12">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-blue-400/20 bg-blue-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-blue-300">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Instrument &amp; Control System
                </div>

                <h2 class="text-4xl font-bold leading-tight xl:text-5xl">
                    Manage reliability.
                    <span class="block text-blue-400">Control performance.</span>
                </h2>

                <p class="mt-6 max-w-lg text-base leading-8 text-slate-300">
                    A centralized platform for daily reporting, equipment
                    maintenance, and operational records to support reliable
                    and efficient plant operations.
                </p>

                <div class="mt-10 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/15 text-blue-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 15l3-4 3 2 5-7"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold">Daily Reporting</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">
                            Structured operational records.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a4 4 0 00-5.4 5.4L4 17v3h3l5.3-5.3a4 4 0 005.4-5.4l-3 3-3-3 3-3z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold">Maintenance</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">
                            Equipment service tracking.
                        </p>
                    </div>
                </div>
            </div>

            <footer class="relative z-10 border-t border-white/10 pt-6">
                <p class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} DRICS. Internal use only.
                </p>
            </footer>
        </section>

        {{-- RIGHT: LOGIN --}}
        <section class="flex min-h-screen items-center justify-center bg-white px-5 py-10 sm:px-10 lg:px-12">

            <div class="w-full max-w-md">

                {{-- Mobile branding --}}
                <div class="mb-12 flex items-center gap-3 lg:hidden">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-wider text-slate-900">DRICS</h1>
                        <p class="text-xs text-slate-500">POWER PLANT DIVISION</p>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-blue-600">
                        Welcome back
                    </p>

                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        Sign in to DRICS
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Enter your employee credentials to access the system.
                    </p>
                </div>

                {{-- Session status --}}
                <x-auth-session-status
                    class="mb-5"
                    :status="session('status')"
                />

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                        <p class="text-sm font-semibold text-red-800">
                            Login failed
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- NIK --}}
                    <div>
                        <label for="nik" class="mb-2 block text-sm font-semibold text-slate-700">
                            Employee ID (NIK)
                        </label>

                        <input
                            id="nik"
                            name="nik"
                            type="text"
                            value="{{ old('nik') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your NIK"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 @error('nik') border-red-400 @enderror"
                        >

                        @error('nik')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                            Password
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 @error('password') border-red-400 @enderror"
                        >

                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            name="remember"
                            type="checkbox"
                            value="1"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600"
                        >

                        <label for="remember_me" class="ml-3 text-sm text-slate-600">
                            Remember me
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-600/20 active:scale-[0.99]"
                    >
                        Sign in
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                        </svg>
                    </button>
                </form>
                <div class="mt-6 text-center">
    <p class="text-sm text-slate-500">
        Belum memiliki akun DRICS?
        <a
            href="{{ route('register') }}"
            class="font-semibold text-blue-600 transition hover:text-blue-700 hover:underline"
        >
            Daftar akun
        </a>
    </p>
</div>

                <div class="mt-10 border-t border-slate-100 pt-6">
                    <div class="flex items-center justify-center gap-2 text-xs text-slate-500">
                        <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                        </svg>
                        Authorized personnel only
                    </div>

                    <p class="mt-3 text-center text-xs text-slate-400">
                        &copy; {{ date('Y') }} DRICS · Instrument &amp; Control System
                    </p>
                </div>

            </div>
        </section>
    </main>

</body>
</html>
