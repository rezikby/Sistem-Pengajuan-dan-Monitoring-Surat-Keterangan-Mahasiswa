@extends('dashboard.admin.layout')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="flex items-start gap-2 bg-green-50 border border-green-200 text-green-600 rounded-xl px-4 py-3 text-sm">
        <i class="bi bi-check-circle-fill mt-0.5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
    <div class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm">
        <i class="bi bi-x-circle-fill mt-0.5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

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
                <span id="currentDate"></span>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm shadow-blue-100">
                <i class="bi bi-download"></i>
                <span>Export Data</span>
            </button>
        </div>
    </div>

    {{-- Statistik Card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Card 1: Total Pengajuan -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-blue-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Pengajuan</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['total'] ?? 0 }}</h2>
                <p class="text-green-600 text-xs mt-2.5 flex items-center font-medium bg-green-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-arrow-up-short text-base me-0.5"></i>Data terbaru
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/50 shadow-sm">
                <i class="bi bi-file-earmark-text text-xl"></i>
            </div>
        </div>

        <!-- Card 2: Diproses -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-amber-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Diproses</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['diproses'] ?? 0 }}</h2>
                <p class="text-amber-600 text-xs mt-2.5 flex items-center font-medium bg-amber-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-clock-history me-1.5"></i>Belum Diverifikasi
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 border border-amber-100/50 shadow-sm">
                <i class="bi bi-hourglass-split text-xl"></i>
            </div>
        </div>

        <!-- Card 3: Selesai -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-emerald-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Selesai</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['disetujui'] ?? 0 }}</h2>
                <p class="text-emerald-600 text-xs mt-2.5 flex items-center font-medium bg-emerald-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-patch-check me-1.5"></i>Surat Selesai
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 border border-emerald-100/50 shadow-sm">
                <i class="bi bi-check-circle text-xl"></i>
            </div>
        </div>

        <!-- Card 4: Ditolak -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-rose-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Ditolak</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['ditolak'] ?? 0 }}</h2>
                <p class="text-rose-600 text-xs mt-2.5 flex items-center font-medium bg-rose-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-exclamation-triangle me-1.5"></i>Berkas TMS
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 border border-rose-100/50 shadow-sm">
                <i class="bi bi-x-circle text-xl"></i>
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
                        <th class="px-6 py-4 border-b border-slate-100 w-16 text-center">No</th>
                        <th class="px-6 py-4 border-b border-slate-100">Nama</th>
                        <th class="px-6 py-4 border-b border-slate-100">NIM</th>
                        <th class="px-6 py-4 border-b border-slate-100">Semester</th>
                        <th class="px-6 py-4 border-b border-slate-100">Keterangan</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center">Lampiran</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                    @if(isset($pengajuan) && $pengajuan->isNotEmpty())
                        @foreach($pengajuan as $index => $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $item->nama }}</td>
                            <td class="px-6 py-4 font-mono text-slate-600">{{ $item->nim }}</td>
                            <td class="px-6 py-4 text-slate-600">Semester {{ $item->semester }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ Str::limit($item->keterangan, 30) ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($item->lampiran)
                                    <a href="{{ $item->lampiran }}" target="_blank" 
                                       class="inline-flex items-center justify-center text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-lg transition" title="Lihat Lampiran">
                                        <i class="bi bi-paperclip text-sm"></i>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openModal({
                                        id: '{{ $item->id }}',
                                        nama: '{{ addslashes($item->nama) }}',
                                        nim: '{{ $item->nim }}',
                                        semester: '{{ $item->semester }}',
                                        prodi: '{{ $item->prodi ?? '-' }}',
                                        jenis_label: '{{ $item->jenis_label }}',
                                        keterangan: '{{ addslashes($item->keterangan ?? '-') }}',
                                        lampiran: '{{ $item->lampiran }}',
                                        status: '{{ $item->status }}',
                                        status_label: '{{ $item->status_label }}',
                                        tanggal: '{{ $item->tanggal }}',
                                        created_at: '{{ $item->created_at }}',
                                        updated_at: '{{ $item->updated_at }}'
                                    })" 
                                            class="action-btn action-btn-view" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="action-btn action-btn-edit" title="Edit" onclick="editData('{{ $item->id }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete" title="Hapus" onclick="deleteData('{{ $item->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="bi bi-database-slash text-4xl text-slate-300"></i>
                                    <p class="text-sm font-medium">Belum ada data</p>
                                    <p class="text-xs">Data pengajuan akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
</div>

<style>
    .action-btn {
        padding: 0.375rem 0.625rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }

    .action-btn-view {
        background: #dbeafe;
        color: #2563eb;
    }

    .action-btn-view:hover {
        background: #bfdbfe;
        transform: scale(1.05);
    }

    .action-btn-edit {
        background: #d1fae5;
        color: #059669;
    }

    .action-btn-edit:hover {
        background: #a7f3d0;
        transform: scale(1.05);
    }

    .action-btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-btn-delete:hover {
        background: #fca5a5;
        transform: scale(1.05);
    }
</style>

<script>
    function editData(id) {
        alert('Edit data dengan ID: ' + id);
    }

    function deleteData(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            alert('Hapus data dengan ID: ' + id);
        }
    }

    function formatDate() {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const now = new Date();
        const day = days[now.getDay()];
        const date = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        
        return `${date} ${month} ${year}, ${day}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('currentDate').textContent = formatDate();
    });
</script>

@endsection