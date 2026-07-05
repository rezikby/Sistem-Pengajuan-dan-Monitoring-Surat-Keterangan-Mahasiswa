<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Riwayat Pengajuan Surat - Mahasiswa</title>
  @vite(['resources/css/app.css'])
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="h-full min-h-screen bg-slate-50 font-sans antialiased flex flex-col justify-between">

  <x-mahasiswa.navbar :user="$user" />

  <main class="max-w-6xl mx-auto px-6 py-10 w-full flex-grow">
    <div class="flex items-center justify-between mb-8">
      <div>
        <p class="text-xs uppercase tracking-widest text-slate-500">Riwayat Pengajuan</p>
        <h1 class="text-3xl font-semibold text-slate-900 mt-2">Semua Pengajuan Surat</h1>
        <p class="text-sm text-slate-500 mt-2">Lihat status pengajuan surat kamu baik aktif kuliah, magang, maupun rekomendasi.</p>
      </div>
      <a href="{{ route('mahasiswa.aktif.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-polman-blue px-4 py-3 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
        <i class="bi bi-arrow-left-short"></i> Kembali ke Dashboard
      </a>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 shadow-sm">
      {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">
      {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
      @if(isset($pengajuan) && $pengajuan->isEmpty())
      <div class="text-center px-6 py-16">
        <i class="bi bi-inbox text-5xl text-slate-300"></i>
        <p class="mt-4 text-slate-500">Belum ada pengajuan surat. Silakan buat pengajuan baru di dashboard.</p>
      </div>
      @else
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
          <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
            <tr>
              <th class="px-5 py-4">No</th>
              <th class="px-5 py-4">Jenis Surat</th>
              <th class="px-5 py-4">Tanggal</th>
              <th class="px-5 py-4">Status</th>
              <th class="px-5 py-4">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @foreach($pengajuan as $index => $item)
            <tr class="hover:bg-slate-50 transition">
              <td class="px-5 py-4 font-medium text-slate-600">{{ $index + 1 }}</td>
              <td class="px-5 py-4 text-slate-800">{{ $item->jenis_label }}</td>
              <td class="px-5 py-4 text-slate-500">{{ $item->created_at->format('d M Y H:i') }}</td>
              <td class="px-5 py-4">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                  {{ $item->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                  {{ $item->status === 'diproses' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                  {{ $item->status === 'diverifikasi' ? 'bg-sky-50 text-sky-700 border border-sky-200' : '' }}
                  {{ $item->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                  {{ $item->status === 'ditolak' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                ">
                  {{ $item->status_label }}
                </span>
              </td>
              <td class="px-5 py-4 text-right space-x-2">
                @if(in_array($item->status, ['pending', 'ditolak']))
                <a href="{{ route("mahasiswa.{$item->jenis}.edit", $item) }}" class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">Edit</a>
                @elseif($item->status === 'disetujui')
                <a href="{{ route('mahasiswa.pengajuan.download', $item->id) }}" class="inline-flex items-center rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">Unduh</a>
                @else
                <span class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-500">Tidak ada aksi</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 text-slate-500 text-xs">
        Menampilkan {{ $pengajuan->count() }} dari total {{ $pengajuan->total() ?? $pengajuan->count() }} pengajuan.
      </div>
      @endif
    </div>
  </main>

  <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400 w-full mt-10">
    &copy; 2026 Politeknik Manufaktur Negeri Bangka Belitung.
  </footer>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
