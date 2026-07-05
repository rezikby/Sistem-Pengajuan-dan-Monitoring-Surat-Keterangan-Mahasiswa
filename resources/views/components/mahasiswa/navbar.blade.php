{{--
    Komponen Navbar Admin - Sistem Pengajuan & Monitoring Surat Keterangan Mahasiswa
    Dependensi: Tailwind CSS, Alpine.js (x-data), Bootstrap Icons (CDN)
--}}

<nav
    x-data="{ 
        isMobileMenuOpen: false,
        dropdownAkademik: false,
        dropdownMahasiswa: false,
        dropdownMatkul: false,
        dropdownKrs: false,
        dropdownMobileAkademik: false,
        dropdownMobileMahasiswa: false,
        dropdownMobileMatkul: false,
        dropdownMobileKrs: false,
        akademikLeft: 0,
        mahasiswaLeft: 0,
        matkulLeft: 0,
        krsLeft: 0
    }"
    class="text-white border-b shadow-lg sticky top-0 z-50"
    style="background-color: #304C81; border-color: rgba(255,255,255,0.1);">

    <div class="container mx-auto px-4 overflow-visible">
        <div class="flex items-center justify-between h-16">

            {{-- Brand / Logo --}}
            <div class="flex items-center gap-3 shrink-0" style="display: flex !important; align-items: center !important;">
                <div class="bg-white rounded-lg shadow-md overflow-hidden"
                    style="width: 36px !important; height: 36px !important; min-width: 36px !important; max-width: 36px !important; min-height: 36px !important; max-height: 36px !important; display: flex !important; align-items: center !important; justify-content: center !important; padding: 4px !important;">

                    <img src="{{ asset('img/logo_polman.png') }}"
                        alt="Logo POLMAN"
                        style="width: auto !important; height: auto !important; max-width: 100% !important; max-height: 100% !important; object-fit: contain !important; display: block !important;">
                </div>

                <div class="leading-tight">
                    <p class="text-white font-semibold text-sm">SIAKAD</p>
                    <p class="text-blue-200 text-xs hidden sm:block">Polman Babel</p>
                </div>
            </div>

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-1 flex-1 justify-center">
                {{-- Dashboard --}}
                <a href="{{ route('mahasiswa.aktif.landing') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm whitespace-nowrap text-white/80">
                    <i class="bi bi-house-door-fill text-base"></i>
                    <span>Dashboard</span>
                </a>

                {{-- Akademik Dropdown --}}
                <div class="relative group">
                    <button @click="dropdownAkademik = !dropdownAkademik; akademikLeft = $el.getBoundingClientRect().left"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm whitespace-nowrap text-white/80">
                        <i class="bi bi-mortarboard text-base"></i>
                        <span>Akademik</span>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="dropdownAkademik ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="dropdownAkademik"
                        @click.away="dropdownAkademik = false"
                        x-transition
                        class="fixed shadow-xl py-1 min-w-[200px] border z-50"
                        :style="`background-color: #304C81; border-color: rgba(255,255,255,0.1); top: 64px; left: ${akademikLeft}px;`">
                        <a href="#" class="block px-4 py-2 text-sm text-white/70">Kalender Akademik</a>
                        <a href="#" class="block px-4 py-2 text-sm text-white/70">Jadwal</a>
                        <a href="#" class="block px-4 py-2 text-sm text-white/70">Kelas</a>
                        <a href="#" class="block px-4 py-2 text-sm text-white/70">Ruangan</a>
                    </div>
                </div>

                {{-- Mahasiswa Dropdown --}}
                <div class="relative group">
                    <button @click="dropdownMahasiswa = !dropdownMahasiswa; mahasiswaLeft = $el.getBoundingClientRect().left"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm whitespace-nowrap text-white/80">
                        <i class="bi bi-person-badge text-base"></i>
                        <span>Mahasiswa</span>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="dropdownMahasiswa ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="dropdownMahasiswa"
                        @click.away="dropdownMahasiswa = false"
                        x-transition
                        class="fixed shadow-xl py-1 min-w-[200px] border z-50"
                        :style="`background-color: #304C81; border-color: rgba(255,255,255,0.1); top: 64px; left: ${mahasiswaLeft}px;`">
                        <a href="#" class="block px-4 py-2 text-sm text-white/70">Data Mahasiswa</a>
                        <a href="#" class="block px-4 py-2 text-sm text-white/70">Status Mahasiswa</a>
                        <a href="#" class="block px-4 py-2 text-sm text-white/70">Import Data</a>
                    </div>
                </div>

                {{-- Mata Kuliah Dropdown --}}
                <div class="relative group">
                    <button @click="dropdownMatkul = !dropdownMatkul; matkulLeft = $el.getBoundingClientRect().left"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm whitespace-nowrap text-white/80">
                        <i class="bi bi-book text-base"></i>
                        <span>Mata Kuliah</span>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="dropdownMatkul ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="dropdownMatkul"
                        @click.away="dropdownMatkul = false"
                        x-transition
                        class="fixed shadow-xl py-1 min-w-[200px] border z-50"
                        :style="`background-color: #304C81; border-color: rgba(255,255,255,0.1); top: 64px; left: ${matkulLeft}px;`">
                        <a href="#" class="block px-4 py-2 text-sm text-white/70">Mata Kuliah</a>
                        <a href="#" class="block px-4 py-2 text-sm text-white/70">Kurikulum</a>
                        <a href="#" class="block px-4 py-2 text-sm text-white/70">Program Studi</a>
                    </div>
                </div>

                {{-- KRS Dropdown --}}
                <div class="relative group">
                    <button @click="dropdownKrs = !dropdownKrs; krsLeft = $el.getBoundingClientRect().left"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm whitespace-nowrap text-white/80">
                        <i class="bi bi-journal-text text-base"></i>
                        <span>KRS</span>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="dropdownKrs ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="dropdownKrs"
                        @click.away="dropdownKrs = false"
                        x-transition
                        class="fixed shadow-xl py-1 min-w-[200px] border z-50"
                        :style="`background-color: #304C81; border-color: rgba(255,255,255,0.1); top: 64px; left: ${krsLeft}px;`">

                        <a href="{{ route('mahasiswa.aktif.dashboard') }}" class="block px-4 py-2 text-sm text-white/70">Pengajuan</a>
                        <a href="{{ route('mahasiswa.riwayat') }}" class="block px-4 py-2 text-sm text-white/70">Riwayat</a>
                    </div>
                </div>

                {{-- Nilai --}}
                <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm whitespace-nowrap text-white/80">
                    <i class="bi bi-bar-chart-line text-base"></i>
                    <span>Nilai</span>
                </a>

                {{-- Pengumuman --}}
                <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm whitespace-nowrap text-white/80">
                    <i class="bi bi-megaphone text-base"></i>
                    <span>Pengumuman</span>
                </a>
            </div>

            {{-- Right Section: User Info & Mobile Toggle --}}
            <div class="flex items-center gap-3">
                {{-- User Info --}}
                <div class="hidden sm:flex items-center gap-2 text-sm">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center border border-white/20">
                        <i class="bi bi-person-circle text-white text-lg"></i>
                    </div>
                    <div class="hidden md:block">
                        <p class="text-white font-medium text-xs">{{ $user->name ?? 'Guest' }}</p>
                        <p class="text-blue-200 text-xs">{{ $user->role ?? 'User' }}</p>
                    </div>
                </div>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg text-white/60 text-sm flex items-center gap-2">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="hidden md:inline">Logout</span>
                    </button>
                </form>

                {{-- Mobile Menu Toggle --}}
                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden text-white/80 p-2 rounded-lg">
                    <i class="bi text-2xl" :class="isMobileMenuOpen ? 'bi-x-lg' : 'bi-list'"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="isMobileMenuOpen"
            x-transition
            class="lg:hidden py-4 border-t space-y-1"
            style="border-color: rgba(255,255,255,0.1);">

            {{-- Dashboard --}}
            <a href="{{ route('mahasiswa.aktif.landing') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-white/80">
                <i class="bi bi-house-door-fill text-lg"></i>
                <span>Dashboard</span>
            </a>

            {{-- Akademik Mobile --}}
            <div>
                <button @click="dropdownMobileAkademik = !dropdownMobileAkademik" class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm text-white/80">
                    <span class="flex items-center gap-3">
                        <i class="bi bi-mortarboard text-lg"></i>
                        <span>Akademik</span>
                    </span>
                    <i class="bi bi-chevron-down text-xs transition-transform" :class="dropdownMobileAkademik ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="dropdownMobileAkademik" class="ml-8 space-y-1 border-l pl-4" style="border-color: rgba(255,255,255,0.15);">
                    <a href="#" class="block px-4 py-2 rounded-md text-sm text-white/70">Kalender Akademik</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-sm text-white/70">Jadwal</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-sm text-white/70">Kelas</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-sm text-white/70">Ruangan</a>
                </div>
            </div>

            {{-- Mahasiswa Mobile --}}
            <div>
                <button @click="dropdownMobileMahasiswa = !dropdownMobileMahasiswa" class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm text-white/80">
                    <span class="flex items-center gap-3">
                        <i class="bi bi-person-badge text-lg"></i>
                        <span>Mahasiswa</span>
                    </span>
                    <i class="bi bi-chevron-down text-xs transition-transform" :class="dropdownMobileMahasiswa ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="dropdownMobileMahasiswa" class="ml-8 space-y-1 border-l pl-4" style="border-color: rgba(255,255,255,0.15);">
                    <a href="#" class="block px-4 py-2 rounded-md text-sm text-white/70">Data Mahasiswa</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-sm text-white/70">Status Mahasiswa</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-sm text-white/70">Import Data</a>
                </div>
            </div>

            {{-- Mata Kuliah Mobile --}}
            <div>
                <button @click="dropdownMobileMatkul = !dropdownMobileMatkul" class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm text-white/80">
                    <span class="flex items-center gap-3">
                        <i class="bi bi-book text-lg"></i>
                        <span>Mata Kuliah</span>
                    </span>
                    <i class="bi bi-chevron-down text-xs transition-transform" :class="dropdownMobileMatkul ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="dropdownMobileMatkul" class="ml-8 space-y-1 border-l pl-4" style="border-color: rgba(255,255,255,0.15);">
                    <a href="#" class="block px-4 py-2 rounded-md text-sm text-white/70">Mata Kuliah</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-sm text-white/70">Kurikulum</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-sm text-white/70">Program Studi</a>
                </div>
            </div>

            {{-- KRS Mobile --}}
            <div>
                <button @click="dropdownMobileKrs = !dropdownMobileKrs" class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm text-white/80">
                    <span class="flex items-center gap-3">
                        <i class="bi bi-journal-text text-lg"></i>
                        <span>KRS</span>
                    </span>
                    <i class="bi bi-chevron-down text-xs transition-transform" :class="dropdownMobileKrs ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="dropdownMobileKrs" class="ml-8 space-y-1 border-l pl-4" style="border-color: rgba(255,255,255,0.15);">
                    <a href="{{ route('mahasiswa.aktif.dashboard') }}" class="block px-4 py-2 rounded-md text-sm text-white/70">Pengajuan</a>
                    <a href="{{ route('mahasiswa.riwayat') }}" class="block px-4 py-2 rounded-md text-sm text-white/70">Riwayat</a>
                </div>
            </div>

            {{-- Nilai Mobile --}}
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-white/80">
                <i class="bi bi-bar-chart-line text-lg"></i>
                <span>Nilai</span>
            </a>

            {{-- Pengumuman Mobile --}}
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-white/80">
                <i class="bi bi-megaphone text-lg"></i>
                <span>Pengumuman</span>
            </a>

            {{-- Mobile Logout --}}
            <div class="pt-2 border-t" style="border-color: rgba(255,255,255,0.1);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-white/60">
                        <i class="bi bi-box-arrow-right text-lg"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>