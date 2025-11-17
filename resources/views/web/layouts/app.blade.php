<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PLENARIA')</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AOS Animations -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Tus estilos personalizados -->
    @include('web.styles.csss')
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
</body>
</html>
