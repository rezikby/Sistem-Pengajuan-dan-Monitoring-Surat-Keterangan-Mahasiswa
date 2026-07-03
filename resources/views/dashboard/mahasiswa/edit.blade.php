<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit {{ $label }}</title>
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

    <p class="text-xs font-semibold tracking-widest uppercase text-[#4f8ef7] mb-2">Edit Pengajuan</p>
    <h1 class="text-2xl font-semibold text-slate-900 mb-1">{{ $label }}</h1>
    <p class="text-sm text-slate-400 mb-8">Perbarui data pengajuan Anda di bawah ini.</p>

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

    <form method="POST" action="{{ route('mahasiswa.pengajuan.update', $pengajuan) }}"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-slate-200 p-6 space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label for="nama" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Nama</label>
          <input id="nama" name="nama" type="text" required
                 value="{{ old('nama', $pengajuan->nama) }}"
                 class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30 focus:border-[#4f8ef7]" />
        </div>
        <div>
          <label for="nim" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">NIM</label>
          <input id="nim" name="nim" type="text" required
                 value="{{ old('nim', $pengajuan->nim) }}"
                 class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30 focus:border-[#4f8ef7]" />
        </div>
      </div>

      <div>
        <label for="semester" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Semester</label>
        <select id="semester" name="semester" required
                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30 focus:border-[#4f8ef7]">
          @for ($i = 1; $i <= 8; $i++)
          <option value="{{ $i }}" {{ old('semester', $pengajuan->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
          @endfor
        </select>
      </div>

      <div>
        <label for="keterangan" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="4"
                  placeholder="Mohon ketikkan keterangan disini..."
                  class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#4f8ef7]/30 focus:border-[#4f8ef7]">{{ old('keterangan', $pengajuan->keterangan) }}</textarea>
      </div>

      <div>
        <label for="lampiran" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Lampiran Pendukung</label>

        @if($pengajuan->lampiran)
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
          <i class="bi bi-paperclip"></i>
          <a href="{{ asset('storage/' . $pengajuan->lampiran) }}" target="_blank" class="text-[#4f8ef7] hover:underline">
            Lihat lampiran saat ini
          </a>
        </div>
        @endif

        <input id="lampiran" name="lampiran" type="file" accept=".pdf,.jpg,.jpeg,.png"
               class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-[#4f8ef7] hover:file:bg-blue-100" />
        <p class="text-xs text-slate-400 mt-1.5">Kosongkan jika tidak ingin mengganti lampiran. Format PDF/JPG/PNG, maksimal 2MB.</p>
      </div>

      <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:bg-slate-100 transition-colors">
          Batal
        </a>
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-[#4f8ef7] hover:bg-[#3a7ae0] active:scale-[.99] text-white text-sm font-semibold shadow-md shadow-blue-100 transition-all">
          Simpan Perubahan
        </button>
      </div>
    </form>

  </main>
</body>

</html>