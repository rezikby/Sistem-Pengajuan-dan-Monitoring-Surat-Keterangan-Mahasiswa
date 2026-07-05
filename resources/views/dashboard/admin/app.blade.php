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
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['total'] }}</h2>
                <p class="text-blue-600 text-xs mt-2.5 flex items-center font-medium bg-blue-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-file-earmark-text me-0.5"></i>Total
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/50 shadow-sm">
                <i class="bi bi-file-earmark-text text-xl"></i>
            </div>
        </div>

        {{-- Card 2: Pending --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-slate-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Pending</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['pending'] }}</h2>
                <p class="text-slate-600 text-xs mt-2.5 flex items-center font-medium bg-slate-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-clock me-1.5"></i>Menunggu
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600 border border-slate-100/50 shadow-sm">
                <i class="bi bi-hourglass-split text-xl"></i>
            </div>
        </div>

        {{-- Card 3: Disetujui --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-emerald-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Disetujui</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['disetujui'] }}</h2>
                <p class="text-emerald-600 text-xs mt-2.5 flex items-center font-medium bg-emerald-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-patch-check me-1.5"></i>Selesai
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
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['ditolak'] }}</h2>
                <p class="text-rose-600 text-xs mt-2.5 flex items-center font-medium bg-rose-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-exclamation-triangle me-1.5"></i>Berkas Ditolak
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

            <div class="h-80 rounded-xl bg-slate-100 p-4">
                <canvas id="pengajuanChart" class="w-full h-full"></canvas>
            </div>

            {{-- Chart.js CDN --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                (function(){
                    const ctx = document.getElementById('pengajuanChart');
                    if (!ctx) return;

                    fetch('/admin/pengajuan/chart?period=year', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(r => r.ok ? r.json() : Promise.reject(r))
                    .then(res => {
                        console.log('Chart response:', res);
                        if (!res.success) return;
                        const labels = res.data.labels;
                        const data = res.data.counts;

                        const totalCount = data.reduce((s, v) => s + (Number(v)||0), 0);
                        if (totalCount === 0) {
                            // show placeholder message when no data
                            ctx.parentElement.innerHTML = '<div class="h-full w-full flex items-center justify-center text-slate-400">Tidak ada data untuk ditampilkan</div>';
                            return;
                        }

                        new Chart(ctx.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Jumlah Pengajuan',
                                    data: data,
                                    backgroundColor: 'rgba(59,130,246,0.6)',
                                    borderColor: 'rgba(59,130,246,1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                                }
                            }
                        });
                    })
                    .catch(err => {
                        console.error('Chart load error', err);
                    });
                })();
            </script>

        </div>

        {{-- Aktivitas --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">

            <h3 class="font-semibold text-lg mb-5">
                Pengajuan Terbaru
            </h3>

            <div class="space-y-4">

                @forelse($pengajuan->take(3) as $item)
                <div class="flex gap-3 pb-4 border-b border-slate-100 last:pb-0 last:border-0">

                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-person text-blue-600"></i>
                    </div>

                    <div class="min-w-0">

                        <h4 class="font-semibold text-sm truncate">
                            {{ $item->nama }}
                        </h4>

                        <p class="text-xs text-slate-500 truncate">
                            {{ $item->jenis_label }}
                        </p>

                        <p class="text-xs text-slate-400 mt-1">
                            {{ $item->tanggal }}
                        </p>

                    </div>

                </div>
                @empty
                <p class="text-sm text-slate-500 text-center py-4">Tidak ada pengajuan terbaru</p>
                @endforelse

            </div>

        </div>

    </div>

    {{-- Tabel Pengajuan Terbaru --}}
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
                
                @forelse($pengajuan as $index => $item)
                <tr class="hover:bg-slate-50/50 transition" data-id="{{ $item->id }}">
                    <td class="px-6 py-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-mono text-slate-600">{{ $item->nim }}</td>
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $item->nama }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $item->tanggal }}</td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusColors = [
                                'pending' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-100', 'label' => 'Pending'],
                                'diproses' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100', 'label' => 'Diproses'],
                                'diverifikasi' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'label' => 'Diverifikasi'],
                                'disetujui' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-100', 'label' => 'Disetujui'],
                                'ditolak' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100', 'label' => 'Ditolak'],
                            ];
                            $color = $statusColors[$item->status] ?? $statusColors['pending'];
                        @endphp
                        <span class="{{ $color['bg'] }} {{ $color['text'] }} border {{ $color['border'] }} px-3 py-1 rounded-full text-xs font-semibold inline-block w-24 text-center shadow-sm">
                            {{ $color['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Kelola -->
                            <button type="button" onclick="openModalLihat('{{ $item->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-100" title="Kelola">
                                <i class="bi bi-gear"></i>
                            </button>

                            <!-- Download generated surat (if approved) -->
                            @if(!empty($item->surat_file) || $item->status === 'disetujui')
                            <a href="{{ url('admin/pengajuan/'.$item->id.'/download') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-emerald-600 hover:bg-green-100 border border-emerald-100" title="Download Surat">
                                <i class="bi bi-download"></i>
                            </a>
                            @endif

                            <!-- Soft Delete / Archive -->
                            <button type="button" onclick="adminSoftDeleteRow('{{ $item->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-100" title="Arsipkan">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                        Tidak ada pengajuan surat
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>
</div>

@endsection

@push('scripts')
<script>
function adminSoftDeleteRow(id) {
    if (!confirm('Arsipkan pengajuan ini?')) return;

    fetch(`/admin/pengajuan/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
    })
    .then(res => res.json())
    .then(json => {
        if (json.success) {
            alert(json.message || 'Pengajuan diarsipkan');
            window.location.reload();
        } else {
            alert(json.message || 'Gagal mengarsipkan pengajuan');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat mengarsipkan');
    });
}
</script>
@endpush