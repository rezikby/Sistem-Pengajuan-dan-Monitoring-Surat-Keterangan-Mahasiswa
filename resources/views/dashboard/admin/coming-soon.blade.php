@extends('dashboard.admin.layout')

@section('title', 'Coming Soon')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
            Fitur Sedang Dikembangkan
        </h2>
        <p class="text-slate-500 mt-1 text-sm">
            Halaman ini akan tersedia dalam versi mendatang.
        </p>
    </div>

    {{-- Coming Soon Content --}}
    <div class="bg-white rounded-2xl p-12 shadow-sm border border-slate-100 text-center">
        <div class="mb-6">
            <i class="bi bi-hammer text-6xl text-slate-300"></i>
        </div>
        <h3 class="text-xl font-semibold text-slate-800 mb-2">
            Fitur Ini Sedang Dikembangkan
        </h3>
        <p class="text-slate-500 mb-6">
            Kami sedang bekerja keras untuk menghadirkan fitur terbaru. Silakan kembali lagi nanti.
        </p>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Dashboard
        </a>
    </div>

</div>

@endsection
