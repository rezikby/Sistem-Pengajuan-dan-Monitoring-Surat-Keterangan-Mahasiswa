<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajukan {{ $label }}</title>
  @vite(['resources/css/app.css'])
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: { DEFAULT: '#4f8ef7', dark: '#3a7ae0' },
          }
        }
      }
    }
  </script>
</head>

<body class="h-full min-h-screen bg-slate-50 font-sans antialiased">

  <header class="bg-white border-b border-slate-200">
    <div class="max-w-3xl mx-auto px-6 h-16 flex items-center gap-3">
      <a href="{{ route('mahasiswa.dashboard') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
        <i class="bi bi-arrow-left text-lg"></i>
      </a>
      <div class="w-9 h-9 rounded-lg bg-[#4f8ef7] flex items-center justify-center shrink-0">
        <i class="bi bi-mortarboard-fill text-white text-lg"></i>
      </div>
      <p class="text-slate-900 font-semibold text-sm">Portal Mahasiswa</p>
    </div>
  </header>

  <main class="max-w-3xl mx-auto px-6 py-10">

    <p class="text-xs font-semibold tracking-widest uppercase text-[#4f8ef7] mb-2">Langkah 2 dari 3 &middot; Isi Form</p>
    <h1 class="text-2xl font-semibold text-slate-900 mb-1">{{ $label }}</h1>
    <p class="text-sm text-slate-400 mb-8">Lengkapi data berikut, lalu klik "Kirim Pengajuan" untuk diverifikasi admin.</p>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-500 rounded-xl px-4 py-3 text-sm mb-6">
      <p class="font-medium mb-1">Periksa kembali data berikut:</p>
      <ul class="list-disc list-inside space-y-0.5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('mahasiswa.surat.submit', $jenis) }}"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-slate-200 p-6 space-y-6">
      @csrf

      {{-- INPUT STRUKTUR STANDAR LAMA (TETAP DI-MAINTAIN) --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label for="nama" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Nama</label>
          <input id="nama" name="nama" type="text" required
                 value="{{ old('nama', $user->name ?? '') }}"
                 class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30 focus:border-[#4f8ef7]" />
        </div>
        <div>
          <label for="nim" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">NIM</label>
          <input id="nim" name="nim" type="text" required
                 value="{{ old('nim', $user->nim ?? '') }}"
                 class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30 focus:border-[#4f8ef7]" />
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label for="semester" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Semester</label>
          <select id="semester" name="semester" required
                  class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30 focus:border-[#4f8ef7]">
            <option value="" disabled {{ old('semester') ? '' : 'selected' }}>Pilih semester</option>
            @for ($i = 1; $i <= 8; $i++)
            <option value="{{ $i }}" {{ old('semester', $user->semester ?? '') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
            @endfor
          </select>
        </div>
        <div>
          <label for="program_studi" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Program Studi</label>
          <input id="program_studi" name="program_studi" type="text" required
                 value="{{ old('program_studi', $user->prodi ?? '') }}"
                 class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30 focus:border-[#4f8ef7]" />
        </div>
      </div>


      {{-- KONDISIONAL: INPUT KHUSUS SURAT MAGANG (Sesuai draf Word Berkas Magang) --}}
      @if($jenis === 'magang')
      <div class="border-t border-slate-100 pt-6 space-y-6">
        <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Informasi Tambahan Instansi Magang</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div>
            <label for="pimpinan_instansi" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Pimpinan Instansi Tujuan</label>
            <input id="pimpinan_instansi" name="pimpinan_instansi" type="text" required placeholder="Contoh: Pimpinan / Manajer HRD" value="{{ old('pimpinan_instansi') }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30" />
          </div>
          <div>
            <label for="instansi_tujuan" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Nama Instansi / Perusahaan</label>
            <input id="instansi_tujuan" name="instansi_tujuan" type="text" required placeholder="Contoh: PT. Timah Tbk" value="{{ old('instansi_tujuan') }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30" />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div>
            <label for="awal_magang" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Tanggal Mulai Magang</label>
            <input id="awal_magang" name="awal_magang" type="date" required value="{{ old('awal_magang') }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30" />
          </div>
          <div>
            <label for="akhir_magang" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Tanggal Selesai Magang</label>
            <input id="akhir_magang" name="akhir_magang" type="date" required value="{{ old('akhir_magang') }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30" />
          </div>
        </div>

        <div>
          <label for="email_mahasiswa" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Email Aktif Mahasiswa</label>
          <input id="email_mahasiswa" name="email_mahasiswa" type="email" required placeholder="nama@example.com" value="{{ old('email_mahasiswa', $user->email ?? '') }}"
                 class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30" />
        </div>
      </div>
      @endif


      {{-- KONDISIONAL: INPUT KHUSUS SURAT REKOMENDASI (Sesuai draf Word Surat Rekomendasi) --}}
      @if($jenis === 'rekomendasi')
      <div class="border-t border-slate-100 pt-6 space-y-6">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Informasi Pemberi & Tujuan Rekomendasi</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div>
            <label for="nama_dosen" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Nama Lengkap Dosen Pemberi Rekomendasi</label>
            <input id="nama_dosen" name="nama_dosen" type="text" required placeholder="Nama Dosen & Gelar" value="{{ old('nama_dosen') }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30" />
          </div>
          <div>
            <label for="nip_dosen" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">NIP / NIDN Dosen</label>
            <input id="nip_dosen" name="nip_dosen" type="text" required placeholder="Nomor Identitas Kerja Dosen" value="{{ old('nip_dosen') }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30" />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div>
            <label for="jabatan_akademik" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Jabatan Akademik Dosen</label>
            <input id="jabatan_akademik" name="jabatan_akademik" type="text" required placeholder="Contoh: Ketua Program Studi / Dosen Wali" value="{{ old('jabatan_akademik') }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30" />
          </div>
          <div>
            <label for="fakultas" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Fakultas / Jurusan Mahasiswa</label>
            <input id="fakultas" name="fakultas" type="text" required placeholder="Contoh: Jurusan Teknik Elektro" value="{{ old('fakultas', $user->fakultas ?? '') }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30" />
          </div>
        </div>

        <div>
          <label for="tujuan_rekomendasi" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Tujuan Penggunaan Surat Rekomendasi</label>
          <input id="tujuan_rekomendasi" name="tujuan_rekomendasi" type="text" required placeholder="Contoh: Pendaftaran Beasiswa Bank Indonesia / Lomba National Welding Competition" value="{{ old('tujuan_rekomendasi') }}"
                 class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30" />
        </div>
      </div>
      @endif


      {{-- INPUT STANDAR BAWAAN LAMA --}}
      <div>
        <label for="keterangan" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Keterangan Tambahan</label>
        <textarea id="keterangan" name="keterangan" rows="3"
                  placeholder="Mohon ketikkan keterangan pendukung disini jika ada..."
                  class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30 focus:border-[#4f8ef7]">{{ old('keterangan') }}</textarea>
      </div>

      <div>
        <label for="lampiran" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Lampiran Pendukung (opsional)</label>
        <input id="lampiran" name="lampiran" type="file" accept=".pdf,.jpg,.jpeg,.png"
               class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-[#4f8ef7] hover:file:bg-blue-100" />
        <p class="text-xs text-slate-400 mt-1.5">Format PDF/JPG/PNG, maksimal 2MB.</p>
      </div>

      <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:bg-slate-100 transition-colors">
          Batal
        </a>
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-[#4f8ef7] hover:bg-[#3a7ae0] active:scale-[.99] text-white text-sm font-semibold shadow-md shadow-blue-100 transition-all">
          Kirim Pengajuan
        </button>
      </div>
    </form>

  </main>
</body>
</html>