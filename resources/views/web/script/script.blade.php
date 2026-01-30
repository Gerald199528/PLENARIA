<script>
    // Inicializar AOS
    AOS.init({
        duration: 1000,
        once: true,
        easing: 'ease-out-cubic'
    });
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 100) {
            navbar.classList.add('navbar-scroll');
        } else {
            navbar.classList.remove('navbar-scroll');
        }
    });
document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    let canCloseOnScroll = true;
    const SCROLL_DEBOUNCE_DELAY = 100;
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('hidden');
            if (!isOpen) {
                canCloseOnScroll = false;
                setTimeout(() => {
                    canCloseOnScroll = true;
                }, SCROLL_DEBOUNCE_DELAY);
            }
        });
        window.addEventListener('scroll', () => {
            const isMenuOpen = !mobileMenu.classList.contains('hidden');
            if (isMenuOpen && canCloseOnScroll) {
                mobileMenu.classList.add('hidden');
            }
        });
    }
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));

            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                if (mobileMenu) {
                    mobileMenu.classList.add('hidden');
                    canCloseOnScroll = true;
                }
            }
        });
    });
});
    // Counter animations
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            element.textContent = Math.floor(start);
            if (start >= target) {
                element.textContent = target.toLocaleString();
                clearInterval(timer);
            }
        }, 16);
    }

    // Intersection Observer for counters
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = {
                    'counter-laws': parseInt(document.getElementById('counter-laws')?.textContent) || 247,
                    'counter-sessions': parseInt(document.getElementById('counter-sessions')?.textContent) || 48,
                    'counter-citizens': parseInt(document.getElementById('counter-citizens')?.textContent) || 12540,
                    'counter-projects': parseInt(document.getElementById('counter-projects')?.textContent) || 89,
                    'participation-count': parseInt(document.getElementById('participation-count')?.textContent) || 3420,
                    'suggestions-count': parseInt(document.getElementById('suggestions-count')?.textContent) || 156,
                    'implemented-count': parseInt(document.getElementById('implemented-count')?.textContent) || 78
                };

                Object.entries(counters).forEach(([id, target]) => {
                    const element = document.getElementById(id);
                    if (element) {
                        animateCounter(element, target);
                    }
                });

                counterObserver.disconnect();
            }
        });
    });
    const heroSection = document.querySelector('#inicio');
    if (heroSection) {
        counterObserver.observe(heroSection);
    }
    // Back to top button
    const backToTopButton = document.getElementById('backToTop');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopButton?.classList.remove('scale-0');
            backToTopButton?.classList.add('scale-100');
        } else {
            backToTopButton?.classList.remove('scale-100');
            backToTopButton?.classList.add('scale-0');
        }
    });

    backToTopButton?.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Loading animation for images
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('load', function() {
            this.classList.remove('img-loading');
        });

        if (!img.complete) {
            img.classList.add('img-loading');
        }
    });
    // Enhanced hover effects for cards
    document.querySelectorAll('.card-hover').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.15)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
// Form validation and submission - SOLO para formularios que NO sean el de derechos de palabra
document.querySelectorAll('form:not(#formularioDerechoPalabra)').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        e.preventDefault();

        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="spinner mr-2"></div> <span class="text-white">Enviando...</span>';
        submitBtn.disabled = true;
    });
});
    // Progressive loading for content
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const contentObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);

    document.querySelectorAll('section').forEach(section => {
        contentObserver.observe(section);
    });

    // Console welcome message
    console.log(
        '%cBienvenido al Portal del Concejo Municipal',
        'color: var(--primary-color); font-size: 20px; font-weight: bold;'
    );
    console.log(
        '%cEste sitio web está optimizado para la transparencia y participación ciudadana.',
        'color: #6b7280; font-size: 14px;'
    );

    // Escuchar eventos de Livewire cuando se actualizan los colores
    document.addEventListener('livewire:navigated', function() {
        if (typeof Livewire !== 'undefined') {
            Livewire.on('colorsUpdated', (data) => {
                actualizarColores(data.primaryColor, data.buttonColor);
            });
        }
    });
    // Función para actualizar los colores
    function actualizarColores(primaryColor, buttonColor) {
        const root = document.documentElement;

        // Actualizar variables CSS
        root.style.setProperty('--primary-color', primaryColor);
        root.style.setProperty('--button-color', buttonColor);
        root.style.setProperty('--gradient-2', `linear-gradient(135deg, ${primaryColor} 0%, ${buttonColor} 100%)`);

        // Guardar en localStorage
        localStorage.setItem('primary_color', primaryColor);
        localStorage.setItem('button_color', buttonColor);

        console.log('✅ Colores actualizados dinámicamente:', { primaryColor, buttonColor });
    }

    // Si accedes nuevamente, cargar colores guardados
    window.addEventListener('load', function() {
        const savedPrimary = localStorage.getItem('primary_color');
        const savedButton = localStorage.getItem('button_color');

        if (savedPrimary && savedButton) {
            actualizarColores(savedPrimary, savedButton);
        }
    });
</script>
