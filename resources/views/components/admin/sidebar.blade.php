{{--
    Komponen Sidebar Admin - Sistem Pengajuan & Monitoring Surat Keterangan Mahasiswa
    Dependensi: Tailwind CSS, Alpine.js (x-data), Bootstrap Icons (CDN)
    Sesuaikan setiap `route('...')` dengan nama route asli di routes/web.php
--}}

<aside
    x-data="{
        open: localStorage.getItem('sidebarOpenMenu') || '',
        toggle(menu) {
            this.open = (this.open === menu) ? '' : menu;
            localStorage.setItem('sidebarOpenMenu', this.open);
        },
        isOpen(menu) { return this.open === menu; }
    }"
    class="h-screen w-72 flex flex-col bg-blue-900 text-blue-300 border-r border-blue-800"
>
    {{-- Brand / Logo --}}
    <div class="flex items-center gap-3 px-5 h-14 border-b border-blue-800 shrink-0">
        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shrink-0">
            <i class="bi bi-mortarboard-fill text-white text-lg"></i>
        </div>
        <div class="leading-tight">
            <p class="text-white font-semibold text-sm">SIAKAD</p>
            <p class="text-blue-400 text-xs">Sistem Akademik</p>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 px-3 py-3 space-y-0.5 text-[13px] overflow-hidden">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors
                  {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-blue-900 hover:text-white' }}">
            <i class="bi bi-house-door-fill w-5 text-center"></i>
            <span>Dashboard</span>
        </a>

        {{-- Akademik --}}
        <div>
            <button type="button" @click="toggle('akademik')"
                class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-colors
                       {{ request()->routeIs('admin.akademik.*') ? 'text-white' : 'hover:bg-blue-600 hover:text-white' }}">
                <span class="flex items-center gap-3">
                    <i class="bi bi-mortarboard w-5 text-center"></i>
                    <span>Akademik</span>
                </span>
                <i class="bi bi-chevron-down text-xs transition-transform" :class="isOpen('akademik') && 'rotate-180'"></i>
            </button>
            <div x-show="isOpen('akademik') || {{ request()->routeIs('admin.akademik.*') ? 'true' : 'false' }}"
                 x-collapse
                 class="ml-8 mt-0.5 space-y-0.5 border-l border-blue-800 pl-3">
                <a href="{{ route('admin.akademik.tahun') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.akademik.tahun') ? 'text-blue-400' : 'hover:text-white' }}">Tahun Akademik</a>
                <a href="{{ route('admin.akademik.kalender') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.akademik.kalender') ? 'text-blue-400' : 'hover:text-white' }}">Kalender Akademik</a>
                <a href="{{ route('admin.akademik.jadwal') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.akademik.jadwal') ? 'text-blue-400' : 'hover:text-white' }}">Jadwal</a>
                <a href="{{ route('admin.akademik.kelas') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.akademik.kelas') ? 'text-blue-400' : 'hover:text-white' }}">Kelas</a>
                <a href="{{ route('admin.akademik.ruangan') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.akademik.ruangan') ? 'text-blue-400' : 'hover:text-white' }}">Ruangan</a>
            </div>
        </div>

        {{-- Mahasiswa --}}
        <div>
            <button type="button" @click="toggle('mahasiswa')"
                class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-colors
                       {{ request()->routeIs('admin.mahasiswa.*') ? 'text-white' : 'hover:bg-blue-600 hover:text-white' }}">
                <span class="flex items-center gap-3">
                    <i class="bi bi-person-badge w-5 text-center"></i>
                    <span>Mahasiswa</span>
                </span>
                <i class="bi bi-chevron-down text-xs transition-transform" :class="isOpen('mahasiswa') && 'rotate-180'"></i>
            </button>
            <div x-show="isOpen('mahasiswa') || {{ request()->routeIs('admin.mahasiswa.*') ? 'true' : 'false' }}"
                 x-collapse
                 class="ml-8 mt-0.5 space-y-0.5 border-l border-blue-800 pl-3">
                <a href="{{ route('admin.mahasiswa.data') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.mahasiswa.data') ? 'text-blue-400' : 'hover:text-white' }}">Data Mahasiswa</a>
                <a href="{{ route('admin.mahasiswa.status') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.mahasiswa.status') ? 'text-blue-400' : 'hover:text-white' }}">Status Mahasiswa</a>
                <a href="{{ route('admin.mahasiswa.import') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.mahasiswa.import') ? 'text-blue-400' : 'hover:text-white' }}">Import Data</a>
            </div>
        </div>

        {{-- Dosen --}}
        <div>
            <button type="button" @click="toggle('dosen')"
                class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-colors
                       {{ request()->routeIs('admin.dosen.*') ? 'text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <span class="flex items-center gap-3">
                    <i class="bi bi-person-workspace w-5 text-center"></i>
                    <span>Dosen</span>
                </span>
                <i class="bi bi-chevron-down text-xs transition-transform" :class="isOpen('dosen') && 'rotate-180'"></i>
            </button>
            <div x-show="isOpen('dosen') || {{ request()->routeIs('admin.dosen.*') ? 'true' : 'false' }}"
                 x-collapse
                 class="ml-8 mt-0.5 space-y-0.5 border-l border-blue-800 pl-3">
                <a href="{{ route('admin.dosen.data') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.dosen.data') ? 'text-blue-400' : 'hover:text-white' }}">Data Dosen</a>
                <a href="{{ route('admin.dosen.beban') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.dosen.beban') ? 'text-blue-400' : 'hover:text-white' }}">Beban Mengajar</a>
            </div>
        </div>

        {{-- Mata Kuliah --}}
        <div>
            <button type="button" @click="toggle('matkul')"
                class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-colors
                       {{ request()->routeIs('admin.matkul.*') ? 'text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <span class="flex items-center gap-3">
                    <i class="bi bi-book w-5 text-center"></i>
                    <span>Mata Kuliah</span>
                </span>
                <i class="bi bi-chevron-down text-xs transition-transform" :class="isOpen('matkul') && 'rotate-180'"></i>
            </button>
            <div x-show="isOpen('matkul') || {{ request()->routeIs('admin.matkul.*') ? 'true' : 'false' }}"
                 x-collapse
                 class="ml-8 mt-0.5 space-y-0.5 border-l border-blue-800 pl-3">
                <a href="{{ route('admin.matkul.index') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.matkul.index') ? 'text-blue-400' : 'hover:text-white' }}">Mata Kuliah</a>
                <a href="{{ route('admin.matkul.kurikulum') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.matkul.kurikulum') ? 'text-blue-400' : 'hover:text-white' }}">Kurikulum</a>
                <a href="{{ route('admin.matkul.prodi') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.matkul.prodi') ? 'text-blue-400' : 'hover:text-white' }}">Program Studi</a>
            </div>
        </div>

        {{-- KRS --}}
        <div>
            <button type="button" @click="toggle('krs')"
                class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-colors
                       {{ request()->routeIs('admin.krs.*') ? 'text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <span class="flex items-center gap-3">
                    <i class="bi bi-journal-text w-5 text-center"></i>
                    <span>KRS</span>
                </span>
                <i class="bi bi-chevron-down text-xs transition-transform" :class="isOpen('krs') && 'rotate-180'"></i>
            </button>
            <div x-show="isOpen('krs') || {{ request()->routeIs('admin.krs.*') ? 'true' : 'false' }}"
                 x-collapse
                 class="ml-8 mt-0.5 space-y-0.5 border-l border-blue-800 pl-3">
                <a href="{{ route('admin.krs.pengajuan') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.krs.pengajuan') ? 'text-blue-400' : 'hover:text-white' }}">Pengajuan</a>
                <a href="{{ route('admin.krs.validasi') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.krs.validasi') ? 'text-blue-400' : 'hover:text-white' }}">Validasi</a>
                <a href="{{ route('admin.krs.riwayat') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.krs.riwayat') ? 'text-blue-400' : 'hover:text-white' }}">Riwayat</a>
            </div>
        </div>

        {{-- Nilai --}}
        <div>
            <button type="button" @click="toggle('nilai')"
                class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-colors
                       {{ request()->routeIs('admin.nilai.*') ? 'text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <span class="flex items-center gap-3">
                    <i class="bi bi-bar-chart-line w-5 text-center"></i>
                    <span>Nilai</span>
                </span>
                <i class="bi bi-chevron-down text-xs transition-transform" :class="isOpen('nilai') && 'rotate-180'"></i>
            </button>
            <div x-show="isOpen('nilai') || {{ request()->routeIs('admin.nilai.*') ? 'true' : 'false' }}"
                 x-collapse
                 class="ml-8 mt-0.5 space-y-0.5 border-l border-blue-800 pl-3">
                <a href="{{ route('admin.nilai.input') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.nilai.input') ? 'text-blue-400' : 'hover:text-white' }}">Input Nilai</a>
                <a href="{{ route('admin.nilai.validasi') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.nilai.validasi') ? 'text-blue-400' : 'hover:text-white' }}">Validasi</a>
                <a href="{{ route('admin.nilai.khs') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.nilai.khs') ? 'text-blue-400' : 'hover:text-white' }}">KHS</a>
                <a href="{{ route('admin.nilai.transkrip') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.nilai.transkrip') ? 'text-blue-400' : 'hover:text-white' }}">Transkrip</a>
            </div>
        </div>

        {{-- Kelulusan --}}
        <div>
            <button type="button" @click="toggle('kelulusan')"
                class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-colors
                       {{ request()->routeIs('admin.kelulusan.*') ? 'text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <span class="flex items-center gap-3">
                    <i class="bi bi-award w-5 text-center"></i>
                    <span>Kelulusan</span>
                </span>
                <i class="bi bi-chevron-down text-xs transition-transform" :class="isOpen('kelulusan') && 'rotate-180'"></i>
            </button>
            <div x-show="isOpen('kelulusan') || {{ request()->routeIs('admin.kelulusan.*') ? 'true' : 'false' }}"
                 x-collapse
                 class="ml-8 mt-0.5 space-y-0.5 border-l border-blue-800 pl-3">
                <a href="{{ route('admin.kelulusan.yudisium') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.kelulusan.yudisium') ? 'text-blue-400' : 'hover:text-white' }}">Yudisium</a>
                <a href="{{ route('admin.kelulusan.wisuda') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.kelulusan.wisuda') ? 'text-blue-400' : 'hover:text-white' }}">Wisuda</a>
                <a href="{{ route('admin.kelulusan.ijazah') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.kelulusan.ijazah') ? 'text-blue-400' : 'hover:text-white' }}">Ijazah</a>
            </div>
        </div>

        {{-- Surat --}}
        <div>
            <button type="button" @click="toggle('surat')"
                class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg transition-colors
                       {{ request()->routeIs('admin.surat.*') ? 'text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <span class="flex items-center gap-3">
                    <i class="bi bi-file-earmark-text w-5 text-center"></i>
                    <span>Surat</span>
                </span>
                <i class="bi bi-chevron-down text-xs transition-transform" :class="isOpen('surat') && 'rotate-180'"></i>
            </button>
            <div x-show="isOpen('surat') || {{ request()->routeIs('admin.surat.*') ? 'true' : 'false' }}"
                 x-collapse
                 class="ml-8 mt-0.5 space-y-0.5 border-l border-blue-800 pl-3">
                <a href="{{ route('admin.surat.akademik') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.surat.akademik') ? 'text-blue-400' : 'hover:text-white' }}">Surat Akademik</a>
                <a href="{{ route('admin.surat.template') }}"
                   class="block px-2 py-1.5 rounded-md {{ request()->routeIs('admin.surat.template') ? 'text-blue-400' : 'hover:text-white' }}">Template</a>
            </div>
        </div>

        <div class="pt-2 mt-1.5 border-t border-blue-800 space-y-0.5">

            {{-- Pengumuman --}}
            <a href="{{ route('admin.pengumuman') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('admin.pengumuman') ? 'bg-blue-600 text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <i class="bi bi-megaphone w-5 text-center"></i>
                <span>Pengumuman</span>
            </a>

            {{-- Laporan --}}
            <a href="{{ route('admin.laporan') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('admin.laporan') ? 'bg-blue-600 text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <i class="bi bi-graph-up-arrow w-5 text-center"></i>
                <span>Laporan</span>
            </a>

            {{-- Pengguna --}}
            <a href="{{ route('admin.pengguna') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('admin.pengguna') ? 'bg-blue-600 text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <i class="bi bi-people w-5 text-center"></i>
                <span>Pengguna</span>
            </a>

            {{-- Pengaturan --}}
            <a href="{{ route('admin.pengaturan') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('admin.pengaturan') ? 'bg-blue-600 text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <i class="bi bi-gear w-5 text-center"></i>
                <span>Pengaturan</span>
            </a>

            {{-- Keamanan --}}
            <a href="{{ route('admin.keamanan') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('admin.keamanan') ? 'bg-blue-600 text-white' : 'hover:bg-blue-800 hover:text-white' }}">
                <i class="bi bi-shield-lock w-5 text-center"></i>
                <span>Keamanan</span>
            </a>
        </div>
    </nav>

    {{-- Logout --}}
    <div class="px-3 py-2.5 border-t border-blue-800 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
                <i class="bi bi-box-arrow-right w-5 text-center"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>