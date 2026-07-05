<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Layanan Surat Mahasiswa - Polman Babel</title>
  @vite(['resources/css/app.css'])
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            polman: {
              blue: '#1e3a8a',
              blueHover: '#172554',
              gray: '#64748b'
            },
          }
        }
      }
    }
  </script>
</head>

<body class="h-full min-h-screen bg-slate-50 font-sans antialiased flex flex-col justify-between">

  {{-- Topbar / Navigation --}}
  <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-800 to-slate-500 flex items-center justify-center shrink-0 shadow-sm">
          <i class="bi bi-mortarboard-fill text-white text-xl"></i>
        </div>
        <div class="leading-tight">
          <p class="text-slate-900 font-bold text-sm tracking-wide">PORTAL LAYANAN SURAT</p>
          <p class="text-polman-blue font-semibold text-xs uppercase tracking-wider">Polman Babel</p>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
          <p class="text-sm font-semibold text-slate-800">{{ $user->name ?? 'Mahasiswa' }}</p>
          <p class="text-xs text-slate-500 font-medium">{{ $user->fakultas ?? '-' }} | {{ $user->prodi ?? '-' }} | {{ $user->nim ?? '-' }}</p>
        </div>
        <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 font-bold">
          <i class="bi bi-person-fill"></i>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="border-l pl-3 border-slate-200">
          @csrf
          <button type="submit" class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-red-600 transition-colors">
            <i class="bi bi-box-arrow-right text-sm"></i>
            <span class="hidden sm:inline">Keluar</span>
          </button>
        </form>
      </div>
    </div>
  </header>

  {{-- Hero Section ala Landing Page Kampus Polman --}}
  <section class="bg-polman-blue text-white shadow-md relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-blue-700/40 via-transparent to-transparent"></div>
    <div class="max-w-6xl mx-auto px-6 py-12 flex flex-col md:flex-row items-center justify-between relative z-10 gap-6">
      <div class="max-w-2xl">
        <span class="text-xs font-bold tracking-widest uppercase bg-blue-600/50 border border-blue-400/30 px-3 py-1 rounded-full text-blue-100">Sistem Informasi Akademik</span>
        <h1 class="text-3xl md:text-4xl font-extrabold mt-4 leading-tight tracking-tight">Politeknik Manufaktur Negeri<br><span class="text-blue-300">Bangka Belitung</span></h1>
        <p class="text-slate-200 text-sm mt-3 leading-relaxed max-w-xl">Selamat datang kembali, <span class="font-semibold text-white">{{ $user->name ?? 'Mahasiswa' }}</span>. Ajukan permohonan Surat Magang / Kerja Praktik dan Surat Rekomendasi Anda secara mandiri, cepat, dan termonitor.</p>
      </div>
      <div class="hidden md:flex w-28 h-28 items-center justify-center bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-inner">
        <div class="text-center font-black text-3xl tracking-tighter text-white">
          <span class="opacity-60">P</span><span>M</span>
        </div>
      </div>
    </div>
  </section>

  {{-- Konten Utama --}}
  <main class="max-w-6xl mx-auto px-6 py-10 w-full flex-grow">

    @if(session('success'))
    <div class="flex items-start gap-2 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-8 shadow-sm">
      <i class="bi bi-check-circle-fill mt-0.5 text-green-600"></i>
      <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-8 shadow-sm">
      <i class="bi bi-x-circle-fill mt-0.5 text-red-600"></i>
      <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Pilih Jenis Surat --}}
    <section class="mb-12">
      <div class="flex items-center justify-between mb-6 border-b border-slate-200 pb-3">
        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <i class="bi bi-file-earmark-text text-polman-blue"></i> Pilih Jenis Layanan Surat
        </h2>
        <span class="text-xs font-bold bg-slate-200 text-slate-600 px-2.5 py-1 rounded-md">Langkah 1 dari 3</span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Surat Aktif Kuliah --}}
        <a href="{{ route('mahasiswa.surat.form', 'aktif') }}"
           class="group bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:border-blue-400/40 transition-all opacity-75 hover:opacity-100 flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 text-xl mb-4 group-hover:bg-blue-500 group-hover:text-white transition-all">
              <i class="bi bi-person-check-fill"></i>
            </div>
            <h3 class="font-semibold text-slate-800 text-base mb-1.5">Surat Keterangan Aktif Kuliah</h3>
            <p class="text-xs text-slate-400 leading-relaxed mb-6">
              Bukti resmi operasional bahwa Anda masih terdaftar aktif mengikuti perkuliahan pada semester berjalan.
            </p>
          </div>
          <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400 mt-auto">
            Ajukan sekarang <i class="bi bi-arrow-right"></i>
          </span>
        </a>

        {{-- Surat Magang / PKL --}}
        <a href="{{ route('mahasiswa.surat.form', 'magang') }}"
           class="group bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:border-polman-blue/40 transition-all flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-polman-blue text-xl mb-4 group-hover:bg-polman-blue group-hover:text-white transition-all shadow-sm">
              <i class="bi bi-briefcase-fill"></i>
            </div>
            <h3 class="font-bold text-slate-900 text-base mb-1.5 group-hover:text-polman-blue transition-colors">Surat Permohonan Magang / KP</h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-6">
              Diajukan untuk pemenuhan kewajiban kurikulum dan peningkatan keterampilan mahasiswa ke Instansi/Perusahaan tujuan magang[cite: 5, 12].
            </p>
          </div>
          <span class="inline-flex items-center gap-1.5 text-xs font-bold text-polman-blue mt-auto group-hover:gap-2.5 transition-all">
            Isi Form Pengajuan <i class="bi bi-arrow-right"></i>
          </span>
        </a>

        {{-- Surat Rekomendasi --}}
        <a href="{{ route('mahasiswa.surat.form', 'rekomendasi') }}"
           class="group bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:border-slate-400/40 transition-all flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 text-xl mb-4 group-hover:bg-slate-700 group-hover:text-white transition-all shadow-sm">
              <i class="bi bi-patch-check-fill"></i>
            </div>
            <h3 class="font-bold text-slate-900 text-base mb-1.5 group-hover:text-slate-700 transition-colors">Surat Rekomendasi Akademik</h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-6">
              Diberikan oleh Dosen/Kaprodi berdasarkan capaian akademik untuk keperluan beasiswa, lomba, atau kegiatan eksternal lainnya[cite: 25, 28, 29].
            </p>
          </div>
          <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 mt-auto group-hover:gap-2.5 transition-all">
            Isi Form Pengajuan <i class="bi bi-arrow-right"></i>
          </span>
        </a>

      </div>
    </section>

    {{-- Statistik Pengajuan --}}
    <section class="mb-12">
      <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Statistik Pengajuan Surat</h2>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs text-slate-500 font-medium">Total Pengajuan</p>
            <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $statistik['total'] ?? 0 }}</p>
          </div>
          <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600"><i class="bi bi-file-earmark-text"></i></div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs text-slate-500 font-medium">Menunggu Verifikasi</p>
            <p class="text-2xl font-bold text-amber-600 mt-0.5">{{ $statistik['pending'] ?? 0 }}</p>
          </div>
          <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600"><i class="bi bi-clock-history"></i></div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs text-slate-500 font-medium">Disetujui & Selesai</p>
            <p class="text-2xl font-bold text-green-600 mt-0.5">{{ $statistik['disetujui'] ?? 0 }}</p>
          </div>
          <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600"><i class="bi bi-check-circle"></i></div>
        </div>
      </div>
    </section>

    {{-- Riwayat Pengajuan --}}
    <section>
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold text-slate-800">Riwayat Pengajuan Terbaru</h2>
        <a href="{{ route('mahasiswa.riwayat') }}" class="text-xs text-polman-blue font-bold hover:underline flex items-center gap-1">
          Lihat Semua <i class="bi bi-chevron-right text-[10px]"></i>
        </a>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if(isset($pengajuan) && $pengajuan->isEmpty())
        <div class="text-center py-12">
          <i class="bi bi-inbox text-4xl text-slate-300"></i>
          <p class="text-slate-400 text-sm mt-2">Belum ada riwayat pengajuan surat.</p>
        </div>
        @elseif(isset($pengajuan))
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-xs font-bold uppercase tracking-wider">
                <th class="px-5 py-3 w-16 text-center">No</th>
                <th class="px-5 py-3">Jenis Surat</th>
                <th class="px-5 py-3">Tanggal Pengajuan</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @foreach($pengajuan as $index => $item)
              <tr class="hover:bg-slate-50/50 transition">
                <td class="px-5 py-3.5 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $item->jenis_label }}</td>
                <td class="px-5 py-3.5 text-slate-500">{{ $item->created_at->format('d M Y H:i') }}</td>
                <td class="px-5 py-3.5">
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                    {{ $item->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                    {{ $item->status === 'diproses' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                    {{ $item->status === 'disetujui' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                    {{ $item->status === 'ditolak' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                  ">
                    {{ $item->status_label }}
                  </span>
                </td>
                <td class="px-5 py-3.5 text-right">
                  <div class="inline-flex items-center gap-1.5">
                    @if($item->status === 'pending' || $item->status === 'ditolak')
                    <a href="{{ route('mahasiswa.pengajuan.edit', $item->id) }}" class="text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition">Edit</a>
                    @elseif($item->status === 'disetujui')
                    <a href="{{ route('mahasiswa.pengajuan.download', $item->id) }}" download class="text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-lg transition flex items-center gap-1"><i class="bi bi-download"></i> Unduh</a>
                    @else
                    <span class="text-xs text-slate-400">-</span>
                    @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </section>
  </main>

  <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400 w-full mt-12">
    &copy; 2026 Politeknik Manufaktur Negeri Bangka Belitung. Hak Cipta Dilindungi Undang-Undang.
  </footer>
</body>
</html>