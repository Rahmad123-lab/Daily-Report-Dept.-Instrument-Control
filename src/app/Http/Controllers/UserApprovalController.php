<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserApprovalController extends Controller
{
    /**
     * Display pending account registrations.
     */
    public function index(): View
    {
        $users = User::query()
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->paginate(15);

        return view('admin.user-approvals.index', compact('users'));
    }

    /**
     * Approve a pending account and assign its role.
     */
    public function approve(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => [
                'required',
                'string',
                Rule::in([
                    'karyawan',
                    'teknisi',
                    'koordinator',
                    'leader',
                    'supervisor',
                    'kepala_departemen',
                    'kepala_divisi',
                    'k3',
                    'admin',
                ]),
            ],
        ]);

        if ($user->is(auth()->user())) {
            return back()->withErrors([
                'approval' => 'Anda tidak dapat menyetujui akun sendiri.',
            ]);
        }

        $approved = DB::transaction(function () use ($user, $validated) {
            $account = User::query()
                ->whereKey($user->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($account->status !== 'pending') {
                return false;
            }

            $account->role = $validated['role'];
            $account->status = 'active';
            $account->save();

            return true;
        });

        if (! $approved) {
            return back()->withErrors([
                'approval' => 'Akun ini sudah diproses atau tidak lagi menunggu persetujuan.',
            ]);
        }

        return redirect()
            ->route('admin.user-approvals.index')
            ->with('success', 'Akun berhasil disetujui dan diaktifkan.');
    }

    /**
     * Reject a pending account.
     */
    public function reject(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->withErrors([
                'approval' => 'Anda tidak dapat menolak akun sendiri.',
            ]);
        }

        $rejected = DB::transaction(function () use ($user) {
            $account = User::query()
                ->whereKey($user->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($account->status !== 'pending') {
                return false;
            }

            $account->status = 'inactive';
            $account->save();

            return true;
        });

        if (! $rejected) {
            return back()->withErrors([
                'approval' => 'Akun ini sudah diproses atau tidak lagi menunggu persetujuan.',
            ]);
        }

        return redirect()
            ->route('admin.user-approvals.index')
            ->with('success', 'Pendaftaran ditolak. Akun dinonaktifkan.');
    }
}
