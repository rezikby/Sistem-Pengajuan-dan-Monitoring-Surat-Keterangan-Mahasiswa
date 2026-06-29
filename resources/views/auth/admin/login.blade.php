<!-- <!DOCTYPE html>
<html lang="id" class="h-full">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Sistem Manajemen</title>
@vite(['resources/css/app.css'])
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full min-h-screen bg-[#070a0f] text-[#dce4f0] flex items-center justify-center font-sans antialiased">

  <!-- amber glow top -->
  <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[500px] h-64 pointer-events-none"
       style="background:radial-gradient(ellipse,rgba(232,160,32,.1) 0%,transparent 70%)"></div>

  <div class="relative w-full max-w-sm px-4 z-10">

    <!-- restricted badge -->
    <div class="flex items-center gap-3 mb-4">
      <div class="flex-1 h-px bg-[#1c2538]"></div>
      <span class="text-[10px] font-bold uppercase tracking-[.15em] text-[#e8a020]/70">🔒 Restricted Area</span>
      <div class="flex-1 h-px bg-[#1c2538]"></div>
    </div>

    <div class="bg-[#0e1420] border border-[#1c2538] border-t-2 border-t-[#e8a020] rounded-xl px-8 py-10 shadow-2xl">

      <!-- icon -->
      <div class="w-12 h-12 rounded-full bg-[#e8a020]/10 border border-[#e8a020]/25 flex items-center justify-center text-xl mx-auto mb-5">
        ⚙️
      </div>

      <h1 class="text-center text-xl font-bold text-white mb-1">Panel Administrator</h1>
      <p class="text-center text-xs text-[#6b7a96] mb-8">Akses khusus petugas akademik</p>

      @if($errors->any())
        <div class="flex items-start gap-2 bg-red-500/8 border border-red-500/25 text-red-400 rounded-lg px-4 py-3 text-sm mb-6">
          <span>⚠</span><span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.post') }}" autocomplete="off" class="space-y-5">
        @csrf

        <div>
          <label for="nim" class="block text-[11px] font-bold uppercase tracking-[.08em] text-[#6b7a96] mb-2">ID / NIM Admin</label>
          <input id="nim" name="nim" type="text" placeholder="Masukkan ID admin"
            value="{{ old('nim') }}" autofocus
            class="w-full bg-[#080c14] border {{ $errors->has('nim') ? 'border-red-500' : 'border-[#1c2538]' }} rounded-lg px-4 py-3 text-white placeholder-[#6b7a96]/50 text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/15 transition" />
          @error('nim')
            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="block text-[11px] font-bold uppercase tracking-[.08em] text-[#6b7a96] mb-2">Password</label>
          <div class="relative">
            <input id="password" name="password" type="password" placeholder="••••••••"
              class="w-full bg-[#080c14] border {{ $errors->has('password') ? 'border-red-500' : 'border-[#1c2538]' }} rounded-lg px-4 py-3 pr-11 text-white placeholder-[#6b7a96]/50 text-sm focus:outline-none focus:border-[#e8a020] focus:ring-2 focus:ring-[#e8a020]/15 transition" />
            <button type="button" onclick="togglePw()"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-[#6b7a96] hover:text-white transition p-1">👁</button>
          </div>
          @error('password')
            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit"
          class="w-full mt-1 bg-gradient-to-r from-[#e8a020] to-[#c45c00] hover:opacity-90 active:scale-[.98] text-[#0a0600] font-bold rounded-lg py-3 text-sm tracking-wide transition-all">
          Masuk ke Panel
        </button>
      </form>

      <p class="text-center text-[11px] text-[#6b7a96]/50 mt-6">Akses tidak sah akan dicatat &amp; dilaporkan.</p>
    </div>
  </div>

  <script>
    function togglePw() {
      const inp = document.getElementById('password');
      inp.type = inp.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html> -->