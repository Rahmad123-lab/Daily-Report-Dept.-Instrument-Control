<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    private const ROLES = [
        'admin',
        'karyawan',
        'teknisi',
        'koordinator',
        'leader',
        'supervisor',
        'kepala_departemen',
        'kepala_divisi',
        'k3',
    ];

    private const STATUSES = [
        'pending',
        'active',
        'inactive',
    ];

    /**
     * Display the employee directory.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $role = (string) $request->query('role', '');
        $status = (string) $request->query('status', '');

        $query = User::query()->select([
            'id',
            'nik',
            'name',
            'email',
            'department',
            'position',
            'role',
            'status',
            'created_at',
        ]);

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('nik', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (in_array($role, self::ROLES, true)) {
            $query->where('role', $role);
        } else {
            $role = '';
        }

        if (in_array($status, self::STATUSES, true)) {
            $query->where('status', $status);
        } else {
            $status = '';
        }

        $employees = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $statistics = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'pending' => User::where('status', 'pending')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
        ];

        $roles = self::ROLES;
        $statuses = self::STATUSES;

        return view('employees.index', compact(
            'employees',
            'statistics',
            'roles',
            'statuses',
            'search',
            'role',
            'status'
        ));
    }

    /**
     * Display employee details.
     */
    public function show(User $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Display the employee edit form.
     */
    public function edit(User $employee)
    {
        $roles = self::ROLES;

        return view('employees.edit', compact(
            'employee',
            'roles'
        ));
    }

    /**
     * Update employee information.
     */
    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'nik' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'nik')->ignore($employee->id),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'nullable',
                'email:rfc',
                'max:255',
                Rule::unique('users', 'email')->ignore($employee->id),
            ],
            'department' => [
                'required',
                'string',
                'max:255',
            ],
            'position' => [
                'nullable',
                'string',
                'max:255',
            ],
            'role' => [
                'required',
                Rule::in(self::ROLES),
            ],
        ]);

        /*
         * Prevent an administrator from removing their own admin role.
         */
        if (
            (int) $employee->id === (int) Auth::id()
            && $validated['role'] !== 'admin'
        ) {
            throw ValidationException::withMessages([
                'role' => 'You cannot remove your own administrator role.',
            ]);
        }

        /*
         * Prevent demoting the last active administrator.
         */
        $isActiveAdmin = $employee->role === 'admin'
            && $employee->status === 'active';

        if ($isActiveAdmin && $validated['role'] !== 'admin') {
            $activeAdminCount = User::query()
                ->where('role', 'admin')
                ->where('status', 'active')
                ->count();

            if ($activeAdminCount <= 1) {
                throw ValidationException::withMessages([
                    'role' => 'The last active administrator cannot be demoted.',
                ]);
            }
        }

        $employee->fill($validated);
        $employee->save();

        return redirect()
            ->route('admin.employees.show', $employee)
            ->with('success', 'Employee information updated successfully.');
    }
    /**
 * Activate or deactivate an employee account.
 */
public function updateStatus(Request $request, User $employee)
{
    $validated = $request->validate([
        'status' => ['required', Rule::in(['active', 'inactive'])],
    ]);

    $newStatus = $validated['status'];

    DB::transaction(function () use ($employee, $newStatus) {
        /*
         * Lock active administrator rows before changing any status.
         * This protects the last active administrator from being
         * deactivated by concurrent requests.
         */
        $activeAdmins = null;

        if ($newStatus === 'inactive') {
            $activeAdmins = User::query()
                ->where('role', 'admin')
                ->where('status', 'active')
                ->lockForUpdate()
                ->get(['id']);
        }

        $target = User::query()
            ->whereKey($employee->getKey())
            ->lockForUpdate()
            ->firstOrFail();

        if ($target->status === 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Pending accounts must be handled through User Approvals.',
            ]);
        }

        if (
            (int) $target->id === (int) Auth::id()
            && $newStatus === 'inactive'
        ) {
            throw ValidationException::withMessages([
                'status' => 'You cannot deactivate your own account.',
            ]);
        }

        if (
            $target->role === 'admin'
            && $target->status === 'active'
            && $newStatus === 'inactive'
            && $activeAdmins !== null
            && $activeAdmins->count() <= 1
        ) {
            throw ValidationException::withMessages([
                'status' => 'The last active administrator cannot be deactivated.',
            ]);
        }

        $target->status = $newStatus;
        $target->save();
    });

    return redirect()
        ->route('admin.employees.show', $employee)
        ->with(
            'success',
            $newStatus === 'active'
                ? 'Employee account activated successfully.'
                : 'Employee account deactivated successfully.'
        );
}
}
