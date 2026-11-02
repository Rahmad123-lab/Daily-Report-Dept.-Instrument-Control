<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new employee account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
                'unique:users,nik',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'lowercase',
                'unique:users,email',
            ],
            'department' => [
                'required',
                'string',
                'max:255',
            ],
            'position' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);

        User::create([
            'nik' => $validated['nik'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'department' => $validated['department'],
            'position' => $validated['position'],

            // Role tidak boleh ditentukan oleh pendaftar.
            'role' => 'karyawan',

            // Akun wajib disetujui admin sebelum login.
            'status' => 'pending',

            'password' => Hash::make($validated['password']),
        ]);

        // Jangan login otomatis sebelum akun disetujui.
        return redirect()
            ->route('login')
            ->with(
                'status',
                'Pendaftaran berhasil dikirim. Akun Anda menunggu persetujuan admin.'
            );
    }
}
