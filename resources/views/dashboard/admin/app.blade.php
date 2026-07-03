@extends('dashboard.admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500">Total Mahasiswa</p>
            <p class="text-2xl font-bold text-slate-800">1.240</p>
        </div>
        {{-- kartu statistik lain --}}
    </div>
@endsection