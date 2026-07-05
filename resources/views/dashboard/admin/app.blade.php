@extends('dashboard.admin.layout')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                Selamat Datang, Administrator
            </h2>
            <p class="text-slate-500 mt-1 text-sm">
                Sistem Informasi Akademik — Kelola pengajuan surat dan dokumen mahasiswa dengan efisien.
            </p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="text-slate-500 text-sm font-medium flex items-center gap-2 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100 whitespace-nowrap">
                <i class="bi bi-calendar3 text-blue-600"></i>
                <span>3 Juli 2026, Kamis</span>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm shadow-blue-100">
                <i class="bi bi-download"></i>
                <span>Export Data</span>
            </button>
        </div>
    </div>

    {{-- Statistik Card Versi Akademik yang Rapi & Clean --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        {{-- Card 1: Total Pengajuan --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-blue-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Pengajuan</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">245</h2>
                <p class="text-green-600 text-xs mt-2.5 flex items-center font-medium bg-green-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-arrow-up-short text-base me-0.5"></i>12% bulan ini
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/50 shadow-sm">
                <i class="bi bi-file-earmark-text text-xl"></i>
            </div>
        </div>

        {{-- Card 2: Diproses --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-amber-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Diproses</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">42</h2>
                <p class="text-amber-600 text-xs mt-2.5 flex items-center font-medium bg-amber-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-clock-history me-1.5"></i>Belum Diverifikasi
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 border border-amber-100/50 shadow-sm">
                <i class="bi bi-hourglass-split text-xl"></i>
            </div>
        </div>

        {{-- Card 3: Selesai --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-emerald-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Selesai</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">180</h2>
                <p class="text-emerald-600 text-xs mt-2.5 flex items-center font-medium bg-emerald-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-patch-check me-1.5"></i>Surat Selesai
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 border border-emerald-100/50 shadow-sm">
                <i class="bi bi-check-circle text-xl"></i>
            </div>
        </div>

        {{-- Card 4: Ditolak --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-rose-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Ditolak</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">23</h2>
                <p class="text-rose-600 text-xs mt-2.5 flex items-center font-medium bg-rose-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-exclamation-triangle me-1.5"></i>Berkas TMS
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 border border-rose-100/50 shadow-sm">
                <i class="bi bi-x-circle text-xl"></i>
            </div>
        </div>

    </div>
    {{-- Grafik & Aktivitas --}}
    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Grafik --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">

            <div class="flex justify-between mb-5">

                <h3 class="font-semibold text-lg">
                    Statistik Pengajuan
                </h3>

                <span class="text-sm text-slate-400">
                    Tahun 2026
                </span>

            </div>

            <div class="h-80 rounded-xl bg-slate-100 flex items-center justify-center">

                <div class="text-center">

                    <i class="bi bi-bar-chart text-5xl text-slate-400"></i>

                    <p class="text-slate-400 mt-3">
                        Grafik Chart.js nanti ditampilkan di sini
                    </p>

                </div>

            </div>

        </div>

        {{-- Aktivitas --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">

            <h3 class="font-semibold text-lg mb-5">
                Aktivitas Terbaru
            </h3>

            <div class="space-y-5">

                <div class="flex gap-3">

                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="bi bi-person text-blue-600"></i>
                    </div>

                    <div>

                        <h4 class="font-semibold text-sm">
                            Mita
                        </h4>

                        <p class="text-sm text-slate-500">
                            Mengajukan Surat Aktif
                        </p>

                    </div>

                </div>

                <div class="flex gap-3">

                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="bi bi-check2 text-green-600"></i>
                    </div>

                    <div>

                        <h4 class="font-semibold text-sm">
                            Admin
                        </h4>

                        <p class="text-sm text-slate-500">
                            Menyetujui Surat Magang
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Tabel Pengajuan Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">Pengajuan Terbaru</h3>
            <a href="#" class="text-blue-600 text-xs font-semibold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 border-b border-slate-100 w-16 text-center">No.</th>
                        <th class="px-6 py-4 border-b border-slate-100">NPM</th>
                        <th class="px-6 py-4 border-b border-slate-100">Nama Mahasiswa</th>
                        <th class="px-6 py-4 border-b border-slate-100">Tanggal</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center w-36">Status</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-600 divide-y divide-slate-100">

                    {{-- Data Contoh 1 --}}
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 text-center font-medium text-slate-400">1</td>
                        <td class="px-6 py-4 font-mono text-slate-600">2201010043</td>
                        <td class="px-6 py-4 font-medium text-slate-800">Mita</td>
                        <td class="px-6 py-4 text-slate-500">02 Juli 2026</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-amber-50 text-amber-600 border border-amber-100 px-3 py-1 rounded-full text-xs font-semibold inline-block w-24 text-center shadow-sm shadow-amber-50">
                                Diproses
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="#" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-100 px-3 py-1.5 rounded-lg transition" title="Kelola Pengajuan">
                                <span>Kelola</span>
                                <i class="bi bi-chevron-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- Data Contoh 2 --}}
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 text-center font-medium text-slate-400">2</td>
                        <td class="px-6 py-4 font-mono text-slate-600">2201010125</td>
                        <td class="px-6 py-4 font-medium text-slate-800">Rezi</td>
                        <td class="px-6 py-4 text-slate-500">01 Juli 2026</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-50 text-green-600 border border-green-100 px-3 py-1 rounded-full text-xs font-semibold inline-block w-24 text-center shadow-sm shadow-green-50">
                                Selesai
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="#" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-100 px-3 py-1.5 rounded-lg transition" title="Kelola Pengajuan">
                                <span>Kelola</span>
                                <i class="bi bi-chevron-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection