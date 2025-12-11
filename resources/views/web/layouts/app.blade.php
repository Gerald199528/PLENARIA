<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PLENARIA')</title>
    @php
        $faviconPath = \App\Models\Setting::get('logo_icon'); 
        $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('default-favicon.ico');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AOS Animations -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Tus estilos personalizados -->
    @include('web.styles.csss')
    
    <!-- PWA Kit Head (Manifest, Meta tags, etc) -->
    {!! PwaKit::head() !!}
</head>
<body>

    {{-- Sección opcional antes del contenido --}}
    @hasSection('before_content')
        @yield('before_content')
    @endif

    {{-- Contenido principal --}}
    @yield('content')

    {{-- Sección opcional después del contenido --}}
    @hasSection('after_content')
        @yield('after_content')
    @endif

    {{-- Botón up opcional --}}
    @hasSection('backToTop')
        @yield('backToTop')
    @endif

    <!-- Scripts -->
    @include('web.script.script')
    
    <!-- PWA Kit Scripts (Service Worker, Install Toast, etc) -->
    {!! PwaKit::scripts() !!}

    {{-- Recargar página después de logout para que Blade se re-ejecute --}}
    @if(session('logout_success'))
    <script>
        window.location.href = '/';
    </script>
    @endif

</body>
</html>