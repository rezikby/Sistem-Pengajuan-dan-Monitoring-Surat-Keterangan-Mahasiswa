<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Masuk &lsaquo; Sistem Manajemen &mdash; Panel Admin</title>
  @vite(['resources/css/app.css'])
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    }

    .wp-input:focus {
      border-color: #2271b1;
      box-shadow: 0 0 0 1px #2271b1;
      outline: 2px solid transparent;
    }

    .wp-btn:active {
      transform: translateY(1px);
    }
  </style>
</head>

<body class="min-h-screen bg-[#f0f0f1] text-[#3c434a] flex items-start justify-center pt-16 pb-8">

  <div class="w-full max-w-[320px] px-4">

    <!-- logo -->
    <div class="flex justify-center mb-6">
      <div class="w-16 h-16 rounded-full bg-[#2271b1] flex items-center justify-center text-white text-2xl shadow-sm">
        ⚙️
      </div>
    </div>

    <!-- login box -->
    <div class="bg-white shadow-[0_1px_3px_rgba(0,0,0,0.13)] rounded px-6 pt-7 pb-5">

      <p class="text-center text-[13px] text-[#646970] mb-5 -mt-1">Panel Administrator &mdash; akses khusus petugas akademik</p>

      @if($errors->any())
      <div class="border-l-4 border-[#d63638] bg-[#fcf0f1] text-[#3c434a] text-[13px] leading-5 rounded-sm px-3 py-3 mb-4">
        {{ $errors->first() }}
      </div>
      @endif

      <form method="POST" action="{{ route('auth.admin.login.submit') }}" autocomplete="off">
        @csrf

        <p class="mb-4">
          <label for="nim" class="block text-[14px] font-medium text-[#1d2327] mb-1">ID / NIM Admin</label>
          <input id="nim" name="nim" type="text" value="{{ old('nim') }}" autofocus autocapitalize="off"
            class="wp-input w-full text-[24px] font-light px-2 py-1 border rounded-[4px] shadow-[0_0_0_transparent] {{ $errors->has('nim') ? 'border-[#d63638]' : 'border-[#8c8f94]' }}" />
          @error('nim')
          <span class="block text-[#d63638] text-[13px] mt-1">{{ $message }}</span>
          @enderror
        </p>

        <p class="mb-2">
          <label for="password" class="block text-[14px] font-medium text-[#1d2327] mb-1">Password</label>
          <div class="relative">
            <input id="password" name="password" type="password"
              class="wp-input w-full text-[24px] font-light px-2 py-1 pr-10 border rounded-[4px] {{ $errors->has('password') ? 'border-[#d63638]' : 'border-[#8c8f94]' }}" />
            <button type="button" onclick="togglePw()" tabindex="-1"
              class="absolute right-0 top-0 h-full w-9 flex items-center justify-center text-[#787c82] hover:text-[#2271b1]">
              👁
            </button>
          </div>
          @error('password')
          <span class="block text-[#d63638] text-[13px] mt-1">{{ $message }}</span>
          @enderror
        </p>

        <p class="flex items-center justify-between mb-5 mt-4">
          <label class="flex items-center gap-2 text-[14px] text-[#3c434a] select-none">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded-sm border-[#8c8f94] text-[#2271b1] focus:ring-[#2271b1]" />
            Ingat Saya
          </label>
        </p>

        <p class="mb-0">
          <button type="submit"
            class="wp-btn w-full bg-[#2271b1] hover:bg-[#135e96] text-white text-[13px] font-medium rounded-[3px] py-[6px] shadow-[0_1px_0_#0a4b78] transition-colors">
            Masuk
          </button>
        </p>
      </form>
    </div>

    <!-- links below box -->
    <div class="flex items-center justify-between mt-4 text-[13px] px-1">
      <a href="{{ route('auth.mahasiswa.login') }}" class="text-[#646970] hover:text-[#2271b1] hover:underline">
        &larr; Kembali ke Login Mahasiswa
      </a>
      <a href="#" class="text-[#646970] hover:text-[#2271b1] hover:underline">
        Lupa Password?
      </a>
    </div>

    <p class="text-center text-[11px] text-[#a7aaad] mt-6">Akses tidak sah akan dicatat &amp; dilaporkan.</p>
  </div>

  <script>
    function togglePw() {
      const inp = document.getElementById('password');
      inp.type = inp.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>

</html>