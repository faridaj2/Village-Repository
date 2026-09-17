<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#064e3b">

    <title>SIDESA — Sistem Informasi Desa {{ $desa->nama_desa ?? 'Waeleman' }} | Kabupaten Buru</title>
    <meta name="description" content="Platform digital resmi Desa {{ $desa->nama_desa ?? 'Waeleman' }} untuk pelayanan administrasi, data kependudukan, kartu keluarga, surat-menyurat, dan informasi publik.">
    <meta name="keywords" content="desa waeleman, sidaes, sistem informasi desa, kecamatan waelata, kabupaten buru, maluku, layanan desa digital, pengumuman desa">
    <meta name="author" content="Pemerintah Desa {{ $desa->nama_desa ?? 'Waeleman' }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="SIDESA — Sistem Informasi Desa {{ $desa->nama_desa ?? 'Waeleman' }}">
    <meta property="og:description" content="Pelayanan desa yang cepat, transparan, dan akuntabel. Satu platform untuk seluruh administrasi desa.">
    <meta property="og:image" content="{{ asset('logo-tagline.png') }}">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="SIDESA — Sistem Informasi Desa {{ $desa->nama_desa ?? 'Waeleman' }}">
    <meta name="twitter:description" content="Pelayanan desa yang cepat, transparan, dan akuntabel.">
    <meta name="twitter:image" content="{{ asset('logo-tagline.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&family=playfair-display:600,700,800&display=swap" rel="stylesheet" />

    @stack('head')

    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js', 'resources/js/landing.js'])

    {{-- JSON-LD --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "GovernmentOrganization",
        "name": "Pemerintah Desa {{ $desa->nama_desa ?? 'Waeleman' }}",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('logo-tagline.png') }}",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Kecamatan Waelata, Kabupaten Buru",
            "addressRegion": "Maluku",
            "addressCountry": "ID"
        }
    }
    </script>
</head>
<body class="font-sans antialiased bg-white text-slate-900 overflow-x-hidden">

    @include('landing._nav')

    <main>
        @include('landing._hero')
        @include('landing._stats')
        @include('landing._profil')
        @include('landing._fitur')
        @include('landing._struktur')
        @include('landing._fasilitas')
        @include('landing._peta')
        @include('landing._pengumuman')
        @include('landing._galeri')
        @include('landing._kontak')
        @include('landing._cta')
    </main>

    @include('landing._footer')

    @stack('scripts')
</body>
</html>
