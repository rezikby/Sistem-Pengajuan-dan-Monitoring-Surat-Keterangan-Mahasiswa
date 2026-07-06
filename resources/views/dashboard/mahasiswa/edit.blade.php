<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Pengajuan {{ $label }} - Polman Babel</title>
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

  {{-- Header --}}
  <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-3xl mx-auto px-6 h-16 flex items-center gap-4">
      <a href="{{ route('mahasiswa.aktif.dashboard') }}" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100 transition">
        <i class="bi bi-arrow-left text-lg"></i>
      </a>
      <div class="leading-tight">
        <h1 class="text-sm font-bold text-slate-800 tracking-tight">Politeknik Manufaktur Negeri Bangka Belitung</h1>
        <p class="text-xs text-slate-500 font-medium">Edit Pengajuan: {{ $label }}</p>
      </div>
    </div>
  </header>

  {{-- Main Content --}}
  <main class="max-w-3xl w-full mx-auto px-6 py-8 flex-grow">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      
      {{-- Form Header Banner --}}
      <div class="p-6 bg-gradient-to-br from-blue-800 to-indigo-900 text-white">
        <span class="text-[10px] bg-white/20 text-white font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Formulir Perubahan Data</span>
        <h2 class="text-xl font-bold mt-2">Perbarui Data Pengajuan Surat</h2>
        <p class="text-xs text-blue-100/80 mt-1">Silakan sesuaikan kembali informasi di bawah ini dengan benar sebelum mengirim ulang berkas.</p>
      </div>

      {{-- Form Kirim --}}
      <form action="{{ route('mahasiswa.' . $pengajuan->jenis . '.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Validasi Error Global --}}
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-xs font-semibold space-y-1">
          <p class="font-bold flex items-center gap-1.5"><i class="bi bi-exclamation-triangle-fill"></i> Terjadi kesalahan pengisian:</p>
          <ul class="list-disc pl-5 space-y-0.5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        {{-- Data Identitas Utama --}}
        <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-4 space-y-4">
          <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
            <i class="bi bi-person-circle text-blue-800"></i> Identitas Mahasiswa
          </h3>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="nama" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
              <input type="text" id="nama" name="nama" value="{{ old('nama', $pengajuan->nama) }}" required
                     class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-800/20 focus:border-blue-800 bg-white" />
            </div>

            <div>
              <label for="nim" class="block text-xs font-bold text-slate-700 mb-1.5">NIM</label>
              <input type="text" id="nim" name="nim" value="{{ old('nim', $pengajuan->nim) }}" required
                     class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-800/20 focus:border-blue-800 bg-white" />
            </div>

            <div>
              <label for="semester" class="block text-xs font-bold text-slate-700 mb-1.5">Semester Berjalan</label>
              <select id="semester" name="semester" required
                      class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-800/20 focus:border-blue-800 bg-white">
                @for ($i = 1; $i <= 8; $i++)
                  <option value="{{ $i }}" {{ old('semester', $pengajuan->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                @endfor
              </select>
            </div>
          </div>
        </div>

        {{-- SURAT MAGANG --}}
        @if($pengajuan->jenis === 'magang')
        <div class="bg-amber-50/40 border border-amber-100 rounded-xl p-4 space-y-4">
          <h3 class="text-xs font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1.5">
            <i class="bi bi-building-gear"></i> Informasi Instansi & Jadwal Magang
          </h3>

          <div class="space-y-4">
            <div>
              <label for="pimpinan_instansi" class="block text-xs font-bold text-slate-700 mb-1.5">Pimpinan Instansi Tujuan (Contoh: Kepala / HRD Manager)</label>
              <input type="text" id="pimpinan_instansi" name="pimpinan_instansi" value="{{ old('pimpinan_instansi', $pengajuan->pimpinan_instansi) }}" required placeholder="Yth. Pimpinan / Direktur Utama"
                     class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
            </div>

            <div>
              <label for="instansi_tujuan" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Instansi / Perusahaan Tujuan</label>
              <input type="text" id="instansi_tujuan" name="instansi_tujuan" value="{{ old('instansi_tujuan', $pengajuan->instansi_tujuan) }}" required placeholder="PT. Timah Tbk, Bangka"
                     class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="awal_magang" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai Magang</label>
                <input type="date" id="awal_magang" name="awal_magang" value="{{ old('awal_magang', $pengajuan->awal_magang ? \Carbon\Carbon::parse($pengajuan->awal_magang)->format('Y-m-20') : '') }}" required
                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
              </div>

              <div>
                <label for="akhir_magang" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Selesai Magang</label>
                <input type="date" id="akhir_magang" name="akhir_magang" value="{{ old('akhir_magang', $pengajuan->akhir_magang ? \Carbon\Carbon::parse($pengajuan->akhir_magang)->format('Y-m-d') : '') }}" required
                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
              </div>
            </div>

            <div>
              <label for="email_mahasiswa" class="block text-xs font-bold text-slate-700 mb-1.5">Email Aktif Mahasiswa (Korespondensi Magang)</label>
              <input type="email" id="email_mahasiswa" name="email_mahasiswa" value="{{ old('email_mahasiswa', $pengajuan->email_mahasiswa) }}" required placeholder="nama@gmail.com"
                     class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 bg-white" />
            </div>
          </div>
        </div>
        @endif

        {{-- SURAT REKOMENDASI --}}
        @if($pengajuan->jenis === 'rekomendasi')
        <div class="bg-emerald-50/40 border border-emerald-100 rounded-xl p-4 space-y-4">
          <h3 class="text-xs font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
            <i class="bi bi-award"></i> Informasi Dosen Pemberi Rekomendasi
          </h3>

          <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="nama_dosen" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Dosen</label>
                <input type="text" id="nama_dosen" name="nama_dosen" value="{{ old('nama_dosen', $pengajuan->nama_dosen) }}" required placeholder="Nama Lengkap & Gelar Dosen"
                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 bg-white" />
              </div>

              <div>
                <label for="nip_dosen" class="block text-xs font-bold text-slate-700 mb-1.5">NIP / NIDN Dosen</label>
                <input type="text" id="nip_dosen" name="nip_dosen" value="{{ old('nip_dosen', $pengajuan->nip_dosen) }}" required placeholder="Nomor Induk Pegawai Dosen"
                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 bg-white" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="jabatan_akademik" class="block text-xs font-bold text-slate-700 mb-1.5">Jabatan Akademik / Struktur</label>
                <input type="text" id="jabatan_akademik" name="jabatan_akademik" value="{{ old('jabatan_akademik', $pengajuan->jabatan_akademik) }}" required placeholder="Contoh: Ketua Program Studi Teknik Informatika"
                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 bg-white" />
              </div>

              <div>
                <label for="fakultas" class="block text-xs font-bold text-slate-700 mb-1.5">Fakultas / Jurusan Mahasiswa</label>
                <input type="text" id="fakultas" name="fakultas" value="{{ old('fakultas', $pengajuan->fakultas) }}" required placeholder="Jurusan Teknik Elektro dan Informatika"
                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 bg-white" />
              </div>
            </div>

            <div>
              <label for="tujuan_rekomendasi" class="block text-xs font-bold text-slate-700 mb-1.5">Tujuan Rekomendasi (Keperluan Penggunaan Surat)</label>
              <input type="text" id="tujuan_rekomendasi" name="tujuan_rekomendasi" value="{{ old('tujuan_rekomendasi', $pengajuan->tujuan_rekomendasi) }}" required placeholder="Contoh: Pendaftaran Beasiswa Bank Indonesia 2026"
                     class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 bg-white" />
            </div>
          </div>
        </div>
        @endif

        {{-- Keterangan & Lampiran --}}
        <div>
          <label for="keterangan" class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Tambahan / Keterangan Keperluan</label>
          <textarea id="keterangan" name="keterangan" rows="4" placeholder="Ketikkan catatan pendukung tambahan di sini jika ada..."
                    class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-800/20 focus:border-blue-800 bg-white">{{ old('keterangan', $pengajuan->keterangan) }}</textarea>
        </div>

        <div>
          <label for="lampiran" class="block text-xs font-bold text-slate-700 mb-1.5">Berkas Lampiran Pendukung (Opsional)</label>
          
          @if($pengajuan->lampiran)
          <div class="inline-flex items-center gap-2 text-xs font-bold text-blue-800 bg-blue-50 border border-blue-100 px-3 py-2 rounded-xl mb-3">
            <i class="bi bi-file-earmark-check-fill"></i>
            <a href="{{ asset('storage/' . $pengajuan->lampiran) }}" target="_blank" class="hover:underline">
              Lihat Lampiran Terunggah Saat Ini
            </a>
          </div>
          @endif

          <input id="lampiran" name="lampiran" type="file" accept=".pdf,.jpg,.jpeg,.png"
                 class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-800 hover:file:bg-blue-100 transition" />
          <p class="text-[11px] text-slate-400 mt-1.5">Kosongkan jika Anda tidak ingin mengganti file lampiran lama. Format: PDF/JPG/PNG (Maksimal 2MB).</p>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
           <a href="{{ route('mahasiswa.aktif.dashboard') }}"
             class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-100 transition text-center">
            Batal
          </a>
          <button type="submit"
                  class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-800 hover:bg-blue-900 transition shadow-sm text-center">
            Simpan Perubahan
          </button>
        </div>

      </form>
    </div>
  </main>

  {{-- Footer --}}
  <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs font-medium text-slate-400">
    <div class="max-w-3xl mx-auto px-6">
      &copy; {{ date('Y') }} Politeknik Manufaktur Negeri Bangka Belitung. Hak Cipta Dilindungi.
    </div>
  </footer>

</body>
</html>