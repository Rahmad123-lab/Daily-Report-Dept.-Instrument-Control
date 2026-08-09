@extends('layouts.admin')

@section('title', 'Dashboard DRICS')

@section('header')
Dashboard
@endsection

@section('content')

<div class="mb-8 bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-sm">
    <h3 class="text-2xl md:text-3xl font-bold">
        Welcome, {{ auth()->user()->name ?? 'Rahmad Joko Susilo Situmorang' }} 👋
    </h3>
    <p class="text-slate-300 mt-2 text-sm md:text-base">
        Monitoring reliability, maintenance activity, and employee competency.
    </p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between transition hover:shadow-md">
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Employee</p>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">120</h2>
        </div>
        <div class="mt-4 flex items-center text-sm font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md w-fit">
            <span>↑ +5 this month</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between transition hover:shadow-md">
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Maintenance</p>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">18</h2>
        </div>
        <div class="mt-4 flex items-center text-sm font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-md w-fit">
            <span class="animate-pulse mr-1.5 h-2 w-2 rounded-full bg-blue-600"></span>
            <span>Running job</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between transition hover:shadow-md">
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Training Progress</p>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">82%</h2>
        </div>
        <div class="mt-4">
            <div class="w-full bg-gray-100 rounded-full h-1.5 mb-2">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 82%"></div>
            </div>
            <span class="text-xs font-medium text-emerald-600">Competency growth</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between transition hover:shadow-md">
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pending Approval</p>
            <h2 class="text-3xl font-bold text-red-600 mt-2">7</h2>
        </div>
        <div class="mt-4 flex items-center text-sm font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-md w-fit">
            <span>⚠️ Need action</span>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6 overflow-hidden">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-lg text-gray-800">Maintenance Monitoring</h3>
            <button class="text-sm text-blue-600 hover:underline font-medium">View All</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-100 text-gray-400 uppercase text-xs tracking-wider font-semibold">
                        <th class="pb-3 pl-2">Equipment</th>
                        <th class="pb-3 text-center">Status</th>
                        <th class="pb-3 text-right pr-2">PIC</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-3.5 pl-2 font-medium text-gray-700">Flowmeter FT-201</td>
                        <td class="py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">
                                Normal
                            </span>
                        </td>
                        <td class="py-3.5 text-right pr-2 text-gray-500">Rahmad</td>
                    </tr>
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-3.5 pl-2 font-medium text-gray-700">Pressure PT-301</td>
                        <td class="py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold bg-amber-100 text-amber-800 rounded-full">
                                Inspection
                            </span>
                        </td>
                        <td class="py-3.5 text-right pr-2 text-gray-500">Andi</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-lg text-gray-800 mb-5">Competency Level</h3>

        <div class="space-y-5">
            <div>
                <div class="flex justify-between mb-1.5 text-sm">
                    <span class="font-medium text-gray-700">PLC</span>
                    <span class="font-bold text-blue-600">85%</span>
                </div>
                <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full" style="width: 85%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between mb-1.5 text-sm">
                    <span class="font-medium text-gray-700">DCS</span>
                    <span class="font-bold text-blue-600">75%</span>
                </div>
                <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full" style="width: 75%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between mb-1.5 text-sm">
                    <span class="font-medium text-gray-700">Calibration</span>
                    <span class="font-bold text-blue-600">90%</span>
                </div>
                <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full" style="width: 90%"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection