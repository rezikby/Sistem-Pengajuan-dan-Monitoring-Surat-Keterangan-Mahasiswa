<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Landing Page - SIAKAD Polman Babel</title>
    @vite(['resources/css/app.css'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .hero-section {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }
        .hero-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 60px 40px 80px;
            background: linear-gradient(transparent, rgba(0,0,0,0.3) 40%, rgba(0,0,0,0.8) 80%, rgba(0,0,0,0.9));
            opacity: 0;
            transition: opacity 0.6s ease;
        }
        .hero-section:hover .hero-overlay {
            opacity: 1;
        }
        .hero-overlay h1 {
            font-size: 4.5rem;
            font-weight: 900;
            color: white;
            text-shadow: 0 4px 30px rgba(0,0,0,0.5);
            line-height: 1.1;
            transform: translateY(30px);
            transition: transform 0.6s ease;
        }
        .hero-section:hover .hero-overlay h1 {
            transform: translateY(0);
        }
        .hero-overlay p {
            color: rgba(255,255,255,0.85);
            font-size: 1.1rem;
            margin-top: 10px;
            transform: translateY(30px);
            transition: transform 0.6s ease 0.1s;
        }
        .hero-section:hover .hero-overlay p {
            transform: translateY(0);
        }
        .navbar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
        }
        .footer-wrapper {
            position: relative;
            z-index: 10;
        }
        @media (max-width: 768px) {
            .hero-overlay h1 {
                font-size: 2.8rem;
            }
            .hero-overlay p {
                font-size: 0.9rem;
            }
            .hero-overlay {
                padding: 40px 20px 60px;
            }
        }
        @media (max-width: 480px) {
            .hero-overlay h1 {
                font-size: 2rem;
            }
            .hero-overlay {
                padding: 30px 15px 50px;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50">

    {{-- Hero Section Full Width Cover --}}
    <section class="hero-section">
        {{-- Navbar di atas gambar --}}
        <div class="navbar-overlay">
            <x-mahasiswa.navbar :user="$user" />
        </div>

        {{-- Gambar Full Cover --}}
        <img src="{{ asset('img/polmanbabel.jpg') }}" 
             alt="Polman Babel" 
             onerror="this.onerror=null; this.src='https://placehold.co/1920x1080/304C81/FFFFFF?text=Polman+Babel'">

        {{-- Overlay dengan Informasi --}}
        <div class="hero-overlay">
            <div class="container mx-auto px-4">
                <h1>POLMAN BABEL</h1>
                <p>Politeknik Manufaktur Negeri Bangka Belitung</p>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <div class="footer-wrapper">
        <x-mahasiswa.footer />
    </div>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
</body>
</html>