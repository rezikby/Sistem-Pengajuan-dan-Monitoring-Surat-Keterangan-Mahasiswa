<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Mahasiswa</title>
  @vite(['resources/css/app.css'])
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              DEFAULT: '#4f8ef7',
              dark: '#3a7ae0'
            },
          }
        }
      }
    }
  </script>
</head>

<body class="h-full min-h-screen bg-slate-50 font-sans antialiased">

  {{-- Topbar --}}
  <header class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-[#4f8ef7] flex items-center justify-center shrink-0">
          <i class="bi bi-mortarboard-fill text-white text-lg"></i>
        </div>
        <div class="leading-tight">
          <p class="text-slate-900 font-semibold text-sm">Portal Mahasiswa</p>
          <p class="text-slate-400 text-xs">Polman Babel</p>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
          <p class="text-sm font-medium text-slate-800">{{ $user->name ?? 'Mahasiswa' }}</p>
          <p class="text-xs text-slate-400">NIM {{ $user->nim ?? '-' }}</p>
        </div>
        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
          <i class="bi bi-person-fill"></i>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
            class="flex items-center gap-1.5 text-xs font-medium text-slate-400 hover:text-red-500 transition-colors">
            <i class="bi bi-box-arrow-right"></i>
            <span class="hidden sm:inline">Keluar</span>
          </button>
        </form>
      </div>
    </div>
  </header>

  {{-- Konten --}}
  <main class="max-w-6xl mx-auto px-6 py-10">

    {{-- Sapaan --}}
    <div class="mb-8">
      <p class="text-xs font-semibold tracking-widest uppercase text-[#4f8ef7] mb-2">Selamat Datang</p>
      <h1 class="text-2xl font-semibold text-slate-900">Halo, {{ $user->name ?? 'Mahasiswa' }} </h1>
      <p class="text-sm text-slate-400 mt-1">Silakan pilih jenis surat yang ingin Anda ajukan di bawah ini.</p>
    </div>

    @if(session('success'))
    <div class="flex items-start gap-2 bg-green-50 border border-green-200 text-green-600 rounded-xl px-4 py-3 text-sm mb-6">
      <span class="mt-0.5">✓</span><span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Pilih Jenis Surat --}}
    <section class="mb-10">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-slate-800">Pilih Jenis Surat</h2>
        <span class="text-xs text-slate-400">Langkah 1 dari 3</span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        {{-- Surat Aktif Kuliah --}}
        <a href="{{ route('mahasiswa.surat.form', 'aktif') }}"
           class="group relative bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:border-[#4f8ef7]/40 transition-all">
          <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-[#4f8ef7] text-xl mb-4 group-hover:bg-[#4f8ef7] group-hover:text-white transition-colors">
            <i class="bi bi-person-check-fill"></i>
          </div>
          <h3 class="font-semibold text-slate-900 mb-1.5">Surat Keterangan Aktif Kuliah</h3>
          <p class="text-sm text-slate-400 leading-relaxed mb-5">
            Bukti resmi bahwa Anda masih terdaftar sebagai mahasiswa aktif pada semester berjalan.
          </p>
          <span class="inline-flex items-center gap-1.5 text-sm font-medium text-[#4f8ef7]">
            Ajukan sekarang
            <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
          </span>
        </a>

        {{-- Surat Magang / PKL --}}
        <a href="{{ route('mahasiswa.surat.form', 'magang') }}"
           class="group relative bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:border-[#4f8ef7]/40 transition-all">
          <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-[#4f8ef7] text-xl mb-4 group-hover:bg-[#4f8ef7] group-hover:text-white transition-colors">
            <i class="bi bi-briefcase-fill"></i>
          </div>
          <h3 class="font-semibold text-slate-900 mb-1.5">Surat Keterangan Magang / PKL</h3>
          <p class="text-sm text-slate-400 leading-relaxed mb-5">
            Surat pengantar resmi untuk keperluan magang atau praktik kerja lapangan di instansi tujuan.
          </p>
          <span class="inline-flex items-center gap-1.5 text-sm font-medium text-[#4f8ef7]">
            Ajukan sekarang
            <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
          </span>
        </a>

        {{-- Surat Rekomendasi --}}
        <a href="{{ route('mahasiswa.surat.form', 'rekomendasi') }}"
           class="group relative bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:border-[#4f8ef7]/40 transition-all">
          <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-[#4f8ef7] text-xl mb-4 group-hover:bg-[#4f8ef7] group-hover:text-white transition-colors">
            <i class="bi bi-file-earmark-text-fill"></i>
          </div>
          <h3 class="font-semibold text-slate-900 mb-1.5">Surat Rekomendasi</h3>
          <p class="text-sm text-slate-400 leading-relaxed mb-5">
            Surat rekomendasi akademik untuk keperluan beasiswa, lomba, organisasi, atau keperluan lainnya.
          </p>
          <span class="inline-flex items-center gap-1.5 text-sm font-medium text-[#4f8ef7]">
            Ajukan sekarang
            <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
          </span>
        </a>

      </div>
    </section>

    {{-- Riwayat Pengajuan (ringkas) --}}
    <section>
      <h2 class="text-base font-semibold text-slate-800 mb-4">Riwayat Pengajuan Terbaru</h2>
      <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
              <th class="text-left font-medium px-5 py-3">Jenis Surat</th>
              <th class="text-left font-medium px-5 py-3">Tanggal Pengajuan</th>
              <th class="text-left font-medium px-5 py-3">Status</th>
              <th class="text-left font-medium px-5 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr>
              <td colspan="4" class="px-5 py-10 text-center text-slate-300">
                Belum ada riwayat pengajuan surat.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </main>
</body>

</html>
