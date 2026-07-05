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
          <p class="text-xs text-slate-400">{{ $user->fakultas ?? '-' }} | {{ $user->prodi ?? '-' }} | {{ $user->nim ?? '-' }}</p>
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
      <i class="bi bi-check-circle-fill mt-0.5"></i>
      <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm mb-6">
      <i class="bi bi-x-circle-fill mt-0.5"></i>
      <span>{{ session('error') }}</span>
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

    {{-- Statistik Pengajuan --}}
    <section class="mb-10">
      <h2 class="text-base font-semibold text-slate-800 mb-4">Statistik Pengajuan Anda</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        
        {{-- Total --}}
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm hover:shadow-md transition">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Total Pengajuan</p>
              <p class="text-2xl font-bold text-slate-800 mt-1">{{ $statistik['total'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
              <i class="bi bi-file-earmark-text"></i>
            </div>
          </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm hover:shadow-md transition">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Pending</p>
              <p class="text-2xl font-bold text-amber-600 mt-1">{{ $statistik['pending'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
              <i class="bi bi-clock-history"></i>
            </div>
          </div>
        </div>

        {{-- Disetujui --}}
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm hover:shadow-md transition">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Disetujui</p>
              <p class="text-2xl font-bold text-green-600 mt-1">{{ $statistik['disetujui'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
              <i class="bi bi-check-circle"></i>
            </div>
          </div>
        </div>

      </div>
    </section>

    {{-- Riwayat Pengajuan --}}
    <section>
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-slate-800">Riwayat Pengajuan</h2>
        <a href="{{ route('mahasiswa.riwayat') }}" class="text-xs text-[#4f8ef7] font-medium hover:underline">
          Lihat Semua
        </a>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        @if(isset($pengajuan) && $pengajuan->isEmpty())
        <div class="text-center py-12">
          <i class="bi bi-inbox text-5xl text-slate-300"></i>
          <p class="text-slate-400 mt-3">Belum ada riwayat pengajuan surat.</p>
          <p class="text-slate-400 text-sm">Silakan ajukan surat pertama Anda di atas.</p>
        </div>
        @elseif(isset($pengajuan))
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
                <th class="text-left font-medium px-5 py-3 w-16">No</th>
                <th class="text-left font-medium px-5 py-3">Nama</th>
                <th class="text-left font-medium px-5 py-3">NIM</th>
                <th class="text-left font-medium px-5 py-3">Jenis Surat</th>
                <th class="text-left font-medium px-5 py-3">Tanggal Pengajuan</th>
                <th class="text-left font-medium px-5 py-3">Status</th>
                <th class="text-left font-medium px-5 py-3">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @foreach($pengajuan as $index => $item)
              <tr class="hover:bg-slate-50/70 transition">
                <td class="px-5 py-3.5 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                <td class="px-5 py-3.5 font-medium text-slate-800">{{ $item->nama }}</td>
                <td class="px-5 py-3.5 font-mono text-slate-600">{{ $item->nim }}</td>
                <td class="px-5 py-3.5">
                  <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm 
                      {{ $item->jenis === 'aktif' ? 'bg-blue-50 text-blue-600' : ($item->jenis === 'magang' ? 'bg-emerald-50 text-emerald-600' : 'bg-purple-50 text-purple-600') }}">
                      <i class="bi {{ $item->jenis === 'aktif' ? 'bi-person-check' : ($item->jenis === 'magang' ? 'bi-briefcase' : 'bi-file-earmark-text') }}"></i>
                    </span>
                    {{ $item->jenis_label }}
                  </div>
                </td>
                <td class="px-5 py-3.5 text-slate-500">{{ $item->created_at->format('d M Y H:i') }}</td>
                <td class="px-5 py-3.5">
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium
                    {{ $item->status === 'pending' ? 'bg-amber-50 text-amber-600 border border-amber-200' : '' }}
                    {{ $item->status === 'diproses' ? 'bg-blue-50 text-blue-600 border border-blue-200' : '' }}
                    {{ $item->status === 'diverifikasi' ? 'bg-indigo-50 text-indigo-600 border border-indigo-200' : '' }}
                    {{ $item->status === 'disetujui' ? 'bg-green-50 text-green-600 border border-green-200' : '' }}
                    {{ $item->status === 'ditolak' ? 'bg-red-50 text-red-600 border border-red-200' : '' }}
                  ">
                    <i class="bi 
                      {{ $item->status === 'pending' ? 'bi-clock-history' : '' }}
                      {{ $item->status === 'diproses' ? 'bi-hourglass-split' : '' }}
                      {{ $item->status === 'diverifikasi' ? 'bi-check-circle' : '' }}
                      {{ $item->status === 'disetujui' ? 'bi-check2-circle' : '' }}
                      {{ $item->status === 'ditolak' ? 'bi-x-circle' : '' }}
                    "></i>
                    {{ $item->status_label }}
                  </span>
                </td>
                    <td class="px-5 py-3.5">
                  <div class="flex items-center gap-1.5">
                    @if($item->status === 'pending' || $item->status === 'ditolak')
                    <a href="{{ route('mahasiswa.pengajuan.edit', $item->id) }}" 
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                      <i class="bi bi-pencil"></i>
                      Edit
                    </a>
                    <form action="{{ route('mahasiswa.pengajuan.destroy', $item->id) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" 
                              class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                        <i class="bi bi-trash"></i>
                        Hapus
                      </button>
                    </form>
                    @else
                      @if($item->status === 'disetujui')
                        <a href="{{ route('mahasiswa.pengajuan.download', $item->id) }}" title="Unduh Surat" download class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition">
                            <i class="bi bi-download"></i>
                        </a>
                      @else
                        <span class="text-xs text-slate-400">-</span>
                      @endif
                    @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="text-center py-12">
          <i class="bi bi-inbox text-5xl text-slate-300"></i>
          <p class="text-slate-400 mt-3">Belum ada riwayat pengajuan surat.</p>
          <p class="text-slate-400 text-sm">Silakan ajukan surat pertama Anda di atas.</p>
        </div>
        @endif
      </div>
    </section>

  </main>
</body>

</html>