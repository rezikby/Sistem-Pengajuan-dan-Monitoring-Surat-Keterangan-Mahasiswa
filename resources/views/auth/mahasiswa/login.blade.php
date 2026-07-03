<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Mahasiswa</title>
  @vite(['resources/css/app.css'])
  <script src="https://cdn.tailwindcss.com"></script>
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
  <style>
    .field input {
      width: 100%;
      background: transparent;
      border: none;
      border-bottom: 1.5px solid #d1d5db;
      border-radius: 0;
      padding: 8px 0;
      font-size: 14px;
      color: #111827;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }

    .field input::placeholder {
      color: #9ca3af;
    }

    .field input:focus {
      border-bottom-color: #4f8ef7;
      box-shadow: 0 1.5px 0 0 #4f8ef7;
    }

    .field input.error {
      border-bottom-color: #ef4444;
    }

    .field input.error:focus {
      box-shadow: 0 1.5px 0 0 #ef4444;
    }
  </style>
</head>

<body class="h-full min-h-screen flex font-sans antialiased">

  {{-- KIRI: Foto gedung full tinggi --}}
  <div class="hidden md:block w-1/2 relative">
    <img
      src="{{ asset('img/polmanbabel.jpg') }}"
      alt="Gedung Polman Babel"
      class="absolute inset-0 w-full h-full object-cover object-center" />
    <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,.6) 0%, rgba(0,0,0,.15) 50%, transparent 100%)"></div>
    <div class="absolute bottom-0 left-0 right-0 p-10">
      <p class="text-xs font-medium tracking-widest uppercase text-white/50 mb-2">Portal Akademik</p>
      <h2 class="text-white font-semibold text-2xl leading-snug">Politeknik Manufaktur<br>Bangka Belitung</h2>
      <p class="text-white/40 text-sm mt-2">Sistem Informasi Akademik Mahasiswa</p>
    </div>
  </div>

  {{-- KANAN: Form full tinggi --}}
  <div class="flex-1 flex flex-col justify-center items-center bg-white px-8 py-12">
    <div class="w-full max-w-sm">

      <div class="mb-10">
        <p class="text-xs font-semibold tracking-widest uppercase text-[#4f8ef7] mb-3">Polman Babel</p>
        <h1 class="text-2xl font-semibold text-gray-900 mb-1">Selamat datang</h1>
        <p class="text-sm text-gray-400">Masuk menggunakan NIM dan password Anda</p>
      </div>

      @if(session('error'))
      <div class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-500 rounded-xl px-4 py-3 text-sm mb-6">
        <span class="mt-0.5">⚠</span><span>{{ session('error') }}</span>
      </div>
      @endif

      <form method="POST" action="{{ route('auth.mahasiswa.login.submit') }}" autocomplete="off" class="space-y-7">
        @csrf

        {{-- NIM --}}
        <div class="field">
          <label for="nim" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">NIM</label>
          <input
            id="nim" name="nim" type="text"
            placeholder="Masukan NIM Anda"
            value="{{ old('nim') }}" autofocus
            class="{{ $errors->has('nim') ? 'error' : '' }}" />
          @error('nim')
          <p class="text-red-400 text-xs mt-1.5">✕ {{ $message }}</p>
          @enderror
        </div>

        {{-- Password --}}
        <div class="field">
          <label for="password" class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Password</label>
          <div class="relative">
            <input
              id="password" name="password" type="password"
              placeholder="Masukkan password"
              style="padding-right:2rem"
              class="{{ $errors->has('password') ? 'error' : '' }}" />
            <button type="button" onclick="togglePw()"
              class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1">
              <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
              <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="hidden">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                <line x1="1" y1="1" x2="23" y2="23" />
              </svg>
            </button>
          </div>
          @error('password')
          <p class="text-red-400 text-xs mt-1.5">✕ {{ $message }}</p>
          @enderror
        </div>

        {{-- Remember --}}
        <label class="flex items-center gap-2.5 text-sm text-gray-400 cursor-pointer">
          <input type="checkbox" name="remember" value="1" class="w-4 h-4 rounded accent-[#4f8ef7]" />
          Ingat saya di perangkat ini
        </label>

        {{-- Submit --}}
        <button type="submit"
          class="w-full bg-[#4f8ef7] hover:bg-[#3a7ae0] active:scale-[.99] text-white font-semibold rounded-xl py-3 text-sm tracking-wide transition-all shadow-md shadow-blue-100">
          Masuk
        </button>
      </form>

      <p class="text-center text-xs text-gray-300 mt-8">Lupa password? Hubungi bagian akademik.</p>

    </div>
  </div>

  <script>
    function togglePw() {
      const inp = document.getElementById('password');
      const on = document.getElementById('eye-icon');
      const off = document.getElementById('eye-off-icon');
      if (inp.type === 'password') {
        inp.type = 'text';
        on.classList.add('hidden');
        off.classList.remove('hidden');
      } else {
        inp.type = 'password';
        on.classList.remove('hidden');
        off.classList.add('hidden');
      }
    }
  </script>
</body>

</html>