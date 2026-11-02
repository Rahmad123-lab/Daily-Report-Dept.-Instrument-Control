@extends('layouts.app')

@section('title', 'Create Training')
@section('header', 'Create Training')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div>
        <a href="{{ route('trainings.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium
                  text-slate-500 transition hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Training
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50/70 px-6 py-5">
            <h1 class="text-xl font-bold text-slate-800">
                New Training Program
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Isi informasi pelatihan yang akan dikelola dalam DRICS.
            </p>
        </div>

        <form method="POST" action="{{ route('trainings.store') }}" class="p-6">
            @csrf
            @include('trainings._form')
        </form>
    </div>
</div>
@endsection