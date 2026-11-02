
@extends('layouts.app')

@section('title', 'Edit Employee')
@section('header', 'Edit Employee')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">Edit Employee</h1>
        <p class="mt-1 text-sm text-slate-500">
            Update employee identification and organizational information.
        </p>
    </div>

    @if ($errors->any())
        <div role="alert"
             class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-semibold">Please review the following errors:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.employees.update', $employee) }}"
          method="POST"
          class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-semibold text-slate-900">Employee Information</h2>
            <p class="mt-1 text-sm text-slate-500">
                Fields marked with * are required.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">

            <div>
                <label for="nik" class="mb-2 block text-sm font-medium text-slate-700">
                    Employee ID (NIK) *
                </label>
                <input id="nik"
                       name="nik"
                       type="text"
                       maxlength="20"
                       required
                       value="{{ old('nik', $employee->nik) }}"
                       autocomplete="off"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              text-sm outline-none focus:border-blue-500
                              focus:ring-2 focus:ring-blue-100">
                @error('nik')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
                    Full Name *
                </label>
                <input id="name"
                       name="name"
                       type="text"
                       maxlength="255"
                       required
                       value="{{ old('name', $employee->name) }}"
                       autocomplete="name"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              text-sm outline-none focus:border-blue-500
                              focus:ring-2 focus:ring-blue-100">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                    Email Address
                </label>
                <input id="email"
                       name="email"
                       type="email"
                       maxlength="255"
                       value="{{ old('email', $employee->email) }}"
                       autocomplete="email"
                       placeholder="employee@example.com"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              text-sm outline-none focus:border-blue-500
                              focus:ring-2 focus:ring-blue-100">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="department" class="mb-2 block text-sm font-medium text-slate-700">
                    Department *
                </label>
                <input id="department"
                       name="department"
                       type="text"
                       maxlength="255"
                       required
                       value="{{ old('department', $employee->department) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              text-sm outline-none focus:border-blue-500
                              focus:ring-2 focus:ring-blue-100">
                @error('department')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="position" class="mb-2 block text-sm font-medium text-slate-700">
                    Position
                </label>
                <input id="position"
                       name="position"
                       type="text"
                       maxlength="255"
                       value="{{ old('position', $employee->position) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              text-sm outline-none focus:border-blue-500
                              focus:ring-2 focus:ring-blue-100">
                @error('position')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="mb-2 block text-sm font-medium text-slate-700">
                    Access Role *
                </label>
                <select id="role"
                        name="role"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                               text-sm outline-none focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">
                    @foreach ($roles as $item)
                        <option value="{{ $item }}"
                            @selected(old('role', $employee->role) === $item)>
                            {{ ucwords(str_replace('_', ' ', $item)) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm text-slate-500">
                    Account status:
                    <span class="font-semibold text-slate-800">
                        {{ ucfirst($employee->status) }}
                    </span>
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Account status is managed separately and cannot be changed here.
                </p>
            </div>
        </div>

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-200
                    bg-slate-50 px-6 py-4 sm:flex-row">
            <a href="{{ route('admin.employees.show', $employee) }}"
               class="inline-flex items-center justify-center rounded-xl border
                      border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold
                      text-slate-700 hover:bg-slate-100">
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600
                           px-5 py-2.5 text-sm font-semibold text-white
                           hover:bg-blue-700 focus:outline-none focus:ring-2
                           focus:ring-blue-500 focus:ring-offset-2">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
