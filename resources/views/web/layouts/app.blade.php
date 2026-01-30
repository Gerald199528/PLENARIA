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


{{-- Botón Back to Top --}}
<button id="backToTop" class="fixed bottom-4 sm:bottom-5 md:bottom-6 lg:bottom-8 right-4 sm:right-5 md:right-6 lg:right-8 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform scale-0 hover:scale-110 z-40 group flex items-center justify-center" style="background: var(--secondary-color); width: 40px; height: 40px;" data-responsive-sizes="{'sm': '44px', 'md': '52px', 'lg': '64px'}">
    <i class="fas fa-chevron-up text-base sm:text-lg md:text-xl lg:text-2xl transition-transform duration-300" style="transform: translateY(0); pointer-events: none;"></i>
</button>

<script>
// Script para redimensionar el botón según el breakpoint
(function() {
    const backToTopBtn = document.getElementById('backToTop');
    if (!backToTopBtn) return;

    function updateButtonSize() {
        const width = window.innerWidth;
        let size = '40px'; // mobile default

        if (width >= 1024) { // lg breakpoint
            size = '64px';
        } else if (width >= 768) { // md breakpoint
            size = '52px';
        } else if (width >= 640) { // sm breakpoint
            size = '44px';
        }

        backToTopBtn.style.width = size;
        backToTopBtn.style.height = size;
    }

    // Inicial
    updateButtonSize();

    // En cambio de ventana
    window.addEventListener('resize', updateButtonSize);

    // Prevenir que el ícono se mueva en hover
    const icon = backToTopBtn.querySelector('i');

    backToTopBtn.addEventListener('mouseenter', function() {
        if (icon) {
            icon.style.transform = 'translateY(0)';
        }
    });

    backToTopBtn.addEventListener('mouseleave', function() {
        if (icon) {
            icon.style.transform = 'translateY(0)';
        }
    });

    // Prevenir que el ícono se mueva al presionar
    backToTopBtn.addEventListener('mousedown', function() {
        if (icon) {
            icon.style.transform = 'translateY(0)';
        }
    });

    backToTopBtn.addEventListener('mouseup', function() {
        if (icon) {
            icon.style.transform = 'translateY(0)';
        }
    });
})();
</script>

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
        {{-- chatbot-PLENARIA--}}
        @include('web.page.chabot_web.index')

</body>
</html>
