@extends('layouts.app')

@section('title', 'Create Competency')
@section('header', 'Create Competency')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <a href="{{ route('competencies.index') }}"
       class="text-sm font-medium text-slate-500 hover:text-blue-600">
        ← Back to Competency
    </a>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 bg-slate-50/70 px-6 py-5">
            <h1 class="text-xl font-bold text-slate-800">
                Create Competency
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Tambahkan master kompetensi baru ke DRICS.
            </p>
        </div>

        <form method="POST" action="{{ route('competencies.store') }}" class="p-6">
            @csrf
            @include('competencies._form')
        </form>

    </div>
</div>
@endsection