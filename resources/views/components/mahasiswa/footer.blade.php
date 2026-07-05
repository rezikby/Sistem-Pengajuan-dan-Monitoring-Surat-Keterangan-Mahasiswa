{{--
    Komponen Footer - SIAKAD Polman Babel
    Dependensi: Tailwind CSS, Bootstrap Icons (CDN)
--}}

<footer class="w-full" style="background-color: #1a2d4e; border-top: 1px solid rgba(255,255,255,0.1);">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            {{-- Column 1: Brand --}}
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <!-- ganti jadi logo polman babel -->
                    <!-- <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                        <i class="bi bi-mortarboard-fill text-white text-lg"></i>
                    </div> -->
                    <div>
                        <p class="text-white font-semibold text-sm">SIAKAD</p>
                        <p class="text-blue-300 text-xs">Polman Babel</p>
                    </div>
                </div>
                <p class="text-white/40 text-sm leading-relaxed">
                    Sistem Informasi Akademik Politeknik Manufaktur Negeri Bangka Belitung.
                </p>
            </div>

            {{-- Column 2: Quick Links --}}
            <div class="col-span-1">
                <h3 class="text-white font-semibold text-sm mb-4">Tautan Cepat</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-white/40 hover:text-white/70 text-sm transition-colors">Beranda</a>
                    </li>
                    <li>
                        <a href="#" class="text-white/40 hover:text-white/70 text-sm transition-colors">Tentang Kami</a>
                    </li>
                    <li>
                        <a href="#" class="text-white/40 hover:text-white/70 text-sm transition-colors">Layanan</a>
                    </li>
                    <li>
                        <a href="#" class="text-white/40 hover:text-white/70 text-sm transition-colors">Kontak</a>
                    </li>
                </ul>
            </div>

            {{-- Column 3: Layanan --}}
            <div class="col-span-1">
                <h3 class="text-white font-semibold text-sm mb-4">Layanan</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-white/40 hover:text-white/70 text-sm transition-colors">Pengajuan Surat</a>
                    </li>
                    <li>
                        <a href="#" class="text-white/40 hover:text-white/70 text-sm transition-colors">Monitoring</a>
                    </li>
                    <li>
                        <a href="#" class="text-white/40 hover:text-white/70 text-sm transition-colors">Riwayat</a>
                    </li>
                    <li>
                        <a href="#" class="text-white/40 hover:text-white/70 text-sm transition-colors">Bantuan</a>
                    </li>
                </ul>
            </div>

            {{-- Column 4: Kontak & Sosial Media --}}
            <div class="col-span-1">
                <h3 class="text-white font-semibold text-sm mb-4">Hubungi Kami</h3>
                <ul class="space-y-2 mb-4">
                    <li class="flex items-center gap-2 text-white/40 text-sm">
                        <i class="bi bi-geo-alt"></i>
                        <span>Jl. Raya Sungailiat - Pangkalpinang, KM. 8</span>
                    </li>
                    <li class="flex items-center gap-2 text-white/40 text-sm">
                        <i class="bi bi-envelope"></i>
                        <span>info@polmanbabel.ac.id</span>
                    </li>
                    <li class="flex items-center gap-2 text-white/40 text-sm">
                        <i class="bi bi-telephone"></i>
                        <span>(0717) 123456</span>
                    </li>
                </ul>
                <div class="flex gap-3">
                    <a href="#" class="text-white/40 hover:text-white/70 transition-colors">
                        <i class="bi bi-instagram text-lg"></i>
                    </a>
                    <a href="#" class="text-white/40 hover:text-white/70 transition-colors">
                        <i class="bi bi-youtube text-lg"></i>
                    </a>
                    <a href="#" class="text-white/40 hover:text-white/70 transition-colors">
                        <i class="bi bi-facebook text-lg"></i>
                    </a>
                    <a href="#" class="text-white/40 hover:text-white/70 transition-colors">
                        <i class="bi bi-twitter-x text-lg"></i>
                    </a>
                    <a href="#" class="text-white/40 hover:text-white/70 transition-colors">
                        <i class="bi bi-linkedin text-lg"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-white/10 mt-8 pt-6 text-center">
            <p class="text-white/30 text-xs">
                &copy; 2026 Politeknik Manufaktur Negeri Bangka Belitung. 
                <span class="hidden sm:inline">Hak Cipta Dilindungi Undang-Undang.</span>
            </p>
        </div>
    </div>
</footer>