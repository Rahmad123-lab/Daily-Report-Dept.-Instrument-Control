<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Registrasi Karyawan | DRICS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 font-sans antialiased text-slate-900">

    <main class="flex min-h-screen items-center justify-center px-4 py-8 sm:px-6">
        <div class="mx-auto w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl sm:p-8">

        {{-- Page Header --}}
        <div class="mb-8">
            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl
                            bg-blue-600 text-white shadow-sm">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="26"
                        height="26"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-xl font-extrabold tracking-wide text-slate-900">
                        DRICS
                    </h1>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">
                        Instrument & Control System
                    </p>
                </div>
            </div>

            <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                Employee Registration
            </p>

            <h2 class="text-3xl font-bold tracking-tight text-slate-900">
                Registrasi Karyawan
            </h2>

            <p class="mt-3 text-sm leading-6 text-slate-500">
                Buat akun DRICS menggunakan NIK karyawan yang terdaftar.
                Akun baru harus disetujui administrator sebelum dapat digunakan.
            </p>
        </div>

        {{-- Registration Information --}}
        <div class="mb-6 flex gap-3 rounded-xl border border-blue-100
                    bg-blue-50 p-4">
            <div class="mt-0.5 shrink-0 text-blue-600">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4"/>
                    <path d="M12 8h.01"/>
                </svg>
            </div>

            <div>
                <p class="text-sm font-semibold text-blue-900">
                    Persetujuan Administrator
                </p>
                <p class="mt-1 text-xs leading-5 text-blue-800">
                    Setelah registrasi berhasil, status akun akan menjadi
                    <span class="font-bold">Pending</span>. Hubungi administrator
                    jika akun belum disetujui.
                </p>
            </div>
        </div>

        {{-- General Error Summary --}}
        @if ($errors->any())
            <div
                id="registration-errors"
                role="alert"
                aria-live="polite"
                class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4"
            >
                <div class="flex items-start gap-3">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="mt-0.5 shrink-0 text-red-600"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8v4"/>
                        <path d="M12 16h.01"/>
                    </svg>

                    <div>
                        <p class="text-sm font-semibold text-red-800">
                            Registrasi belum berhasil
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1
                                   text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Registration Form --}}
        <form
            method="POST"
            action="{{ route('register') }}"
            class="space-y-5"
        >
            @csrf

            {{-- NIK --}}
            <div>
                <x-input-label
                    for="nik"
                    value="NIK Karyawan *"
                    class="font-semibold text-slate-700"
                />

                <x-text-input
                    id="nik"
                    name="nik"
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]+"
                    maxlength="20"
                    minlength="1"
                    class="mt-2 block w-full rounded-xl border-slate-300
                           bg-white px-4 py-3 text-sm text-slate-900
                           placeholder:text-slate-400
                           focus:border-blue-500 focus:ring-blue-500"
                    :value="old('nik')"
                    placeholder="Masukkan NIK karyawan"
                    required
                    autofocus
                    autocomplete="username"
                    aria-describedby="nik-help nik-error"
                    aria-invalid="{{ $errors->has('nik') ? 'true' : 'false' }}"
                />

                <p id="nik-help" class="mt-2 text-xs text-slate-500">
                    Gunakan nomor induk karyawan yang valid.
                </p>

                <div id="nik-error">
                    <x-input-error
                        :messages="$errors->get('nik')"
                        class="mt-2"
                    />
                </div>
            </div>

            {{-- Full Name --}}
            <div>
                <x-input-label
                    for="name"
                    value="Nama Lengkap *"
                    class="font-semibold text-slate-700"
                />

                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    maxlength="255"
                    class="mt-2 block w-full rounded-xl border-slate-300
                           bg-white px-4 py-3 text-sm text-slate-900
                           placeholder:text-slate-400
                           focus:border-blue-500 focus:ring-blue-500"
                    :value="old('name')"
                    placeholder="Masukkan nama lengkap"
                    required
                    autocomplete="name"
                    aria-describedby="name-error"
                    aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                />

                <div id="name-error">
                    <x-input-error
                        :messages="$errors->get('name')"
                        class="mt-2"
                    />
                </div>
            </div>

            {{-- Email --}}
            <div>
                <x-input-label
                    for="email"
                    value="Email Pemulihan Password *"
                    class="font-semibold text-slate-700"
                />

                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    maxlength="255"
                    class="mt-2 block w-full rounded-xl border-slate-300
                           bg-white px-4 py-3 text-sm text-slate-900
                           placeholder:text-slate-400
                           focus:border-blue-500 focus:ring-blue-500"
                    :value="old('email')"
                    placeholder="nama@perusahaan.com"
                    required
                    autocomplete="email"
                    aria-describedby="email-help email-error"
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                />

                <p id="email-help" class="mt-2 text-xs text-slate-500">
                    Gunakan email aktif untuk kebutuhan pemulihan password.
                </p>

                <div id="email-error">
                    <x-input-error
                        :messages="$errors->get('email')"
                        class="mt-2"
                    />
                </div>
            </div>

            {{-- Department --}}
            <div>
                <x-input-label
                    for="department"
                    value="Departemen *"
                    class="font-semibold text-slate-700"
                />

                <x-text-input
                    id="department"
                    name="department"
                    type="text"
                    maxlength="255"
                    class="mt-2 block w-full rounded-xl border-slate-300
                           bg-white px-4 py-3 text-sm text-slate-900
                           placeholder:text-slate-400
                           focus:border-blue-500 focus:ring-blue-500"
                    :value="old('department', 'Instrument & Control')"
                    required
                    autocomplete="organization"
                    aria-describedby="department-error"
                    aria-invalid="{{ $errors->has('department') ? 'true' : 'false' }}"
                />

                <div id="department-error">
                    <x-input-error
                        :messages="$errors->get('department')"
                        class="mt-2"
                    />
                </div>
            </div>

            {{-- Position --}}
            <div>
                <x-input-label
                    for="position"
                    value="Jabatan *"
                    class="font-semibold text-slate-700"
                />

                <x-text-input
                    id="position"
                    name="position"
                    type="text"
                    maxlength="255"
                    class="mt-2 block w-full rounded-xl border-slate-300
                           bg-white px-4 py-3 text-sm text-slate-900
                           placeholder:text-slate-400
                           focus:border-blue-500 focus:ring-blue-500"
                    :value="old('position')"
                    placeholder="Contoh: Instrument Engineer"
                    required
                    autocomplete="organization-title"
                    aria-describedby="position-error"
                    aria-invalid="{{ $errors->has('position') ? 'true' : 'false' }}"
                />

                <div id="position-error">
                    <x-input-error
                        :messages="$errors->get('position')"
                        class="mt-2"
                    />
                </div>
            </div>

            {{-- Password --}}
            <div>
                <x-input-label
                    for="password"
                    value="Password *"
                    class="font-semibold text-slate-700"
                />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-2 block w-full rounded-xl border-slate-300
                           bg-white px-4 py-3 text-sm text-slate-900
                           placeholder:text-slate-400
                           focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Buat password yang kuat"
                    required
                    autocomplete="new-password"
                    aria-describedby="password-help password-error"
                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                />

                <p id="password-help" class="mt-2 text-xs text-slate-500">
                    Gunakan password yang kuat dan tidak digunakan pada akun lain.
                </p>

                <div id="password-error">
                    <x-input-error
                        :messages="$errors->get('password')"
                        class="mt-2"
                    />
                </div>
            </div>

            {{-- Password Confirmation --}}
            <div>
                <x-input-label
                    for="password_confirmation"
                    value="Konfirmasi Password *"
                    class="font-semibold text-slate-700"
                />

                <x-text-input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="mt-2 block w-full rounded-xl border-slate-300
                           bg-white px-4 py-3 text-sm text-slate-900
                           placeholder:text-slate-400
                           focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Masukkan ulang password"
                    required
                    autocomplete="new-password"
                    aria-describedby="password-confirmation-error"
                    aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                />

                <div id="password-confirmation-error">
                    <x-input-error
                        :messages="$errors->get('password_confirmation')"
                        class="mt-2"
                    />
                </div>
            </div>

            {{-- Actions --}}
            <div class="space-y-4 pt-2">

                <x-primary-button
                    type="submit"
                    class="inline-flex w-full justify-center rounded-xl
                           bg-blue-600 px-5 py-3 text-sm font-bold text-white
                           shadow-sm transition hover:bg-blue-700
                           focus:outline-none focus:ring-2
                           focus:ring-blue-500 focus:ring-offset-2"
                >
                    Daftar Akun
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="ml-2"
                        aria-hidden="true"
                    >
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </x-primary-button>

                <p class="text-center text-sm text-slate-500">
                    Sudah memiliki akun DRICS?
                    <a
                        href="{{ route('login') }}"
                        class="font-semibold text-blue-600 hover:text-blue-700 hover:underline"
                    >
                        Masuk di sini
                    </a>
                </p>
            </div>
        </form>

        {{-- Footer --}}
        <div class="mt-8 border-t border-slate-200 pt-5">
            <div class="flex items-center justify-center gap-2 text-xs text-slate-500">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="text-emerald-600"
                    aria-hidden="true"
                >
                    <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>

                <span>DRICS · Authorized Personnel Only</span>
            </div>

            <p class="mt-3 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} DRICS. Instrument &amp; Control System.
            </p>
        </div>
            </div>
    </main>

</body>
</html>