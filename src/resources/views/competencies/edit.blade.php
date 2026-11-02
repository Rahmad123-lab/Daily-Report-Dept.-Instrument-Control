@extends('layouts.app')

@section('title', 'Edit Competency')
@section('header', 'Edit Competency')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <a href="{{ route('competencies.show', $competency) }}"
       class="text-sm font-medium text-slate-500 hover:text-blue-600">
        ← Back to Competency Details
    </a>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 bg-slate-50/70 px-6 py-5">
            <h1 class="text-xl font-bold text-slate-800">
                Edit Competency
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                {{ $competency->code }} · {{ $competency->name }}
            </p>
        </div>

        <form method="POST"
              action="{{ route('competencies.update', $competency) }}"
              class="p-6">
            @csrf
            @method('PUT')

            @include('competencies._form')
        </form>

    </div>
</div>
@endsection