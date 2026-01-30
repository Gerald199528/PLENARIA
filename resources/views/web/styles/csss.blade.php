<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- AOS CSS - Solo en Desktop -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" media="(min-width: 768px)">
<!-- Script para desactivar AOS en móvil -->
<script>
    const isMobile = window.innerWidth < 768;    
    if (isMobile) {     
        window.AOS = { init: () => {} };
    } else {   
        const aosScript = document.createElement('script');
        aosScript.src = 'https://unpkg.com/aos@2.3.1/dist/aos.js';
        aosScript.async = true;
        document.head.appendChild(aosScript);
    }
</script>
<!-- Tailwind Estilos Principales -->
<style>
    :root {
        --primary-color: {{ \App\Models\Setting::get('primary_color', '#1d4ed8') }};
        --button-color: {{ \App\Models\Setting::get('button_color', '#1d4ed8') }};
        --secondary-color: {{ \App\Models\Setting::get('secondary_color', '#3b82f6') }};
        --accent-color: #2563eb;
        --light-bg: #f8fafc;
        --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-2: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        --shadow-soft: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 8px 25px rgba(0, 0, 0, 0.1), 0 3px 10px rgba(0, 0, 0, 0.05);
        --shadow-large: 0 20px 25px rgba(0, 0, 0, 0.1), 0 8px 10px rgba(0, 0, 0, 0.04);
    }
</style>
<!-- Tailwind Config y Estilos -->
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: 'var(--primary-color, #1d4ed8)',
                    secondary: 'var(--secondary-color, #3b82f6)',
                    accent: 'var(--primary-color, #2563eb)',
                    success: '#10b981',
                    warning: '#f59e0b',
                    danger: '#ef4444',
                    slate: '#64748b'
                },
                fontFamily: {
                    'sans': ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                },
                animation: {
                    'fade-in-up': 'fadeInUp 0.5s ease-out',
                    'fade-in-down': 'fadeInDown 0.5s ease-out',
                    'scale-in': 'scaleIn 0.3s ease-out',
                    'slide-in-left': 'slideInLeft 0.5s ease-out',
                    'slide-in-right': 'slideInRight 0.5s ease-out',
                    'bounce-slow': 'bounce 2s infinite',
                    'pulse-slow': 'pulse 3s infinite',
                    'float': 'float 6s ease-in-out infinite'
                },
                keyframes: {
                    fadeInUp: {
                        '0%': { opacity: '0', transform: 'translateY(30px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' }
                    },
                    fadeInDown: {
                        '0%': { opacity: '0', transform: 'translateY(-30px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' }
                    },
                    scaleIn: {
                        '0%': { opacity: '0', transform: 'scale(0.9)' },
                        '100%': { opacity: '1', transform: 'scale(1)' }
                    },
                    slideInLeft: {
                        '0%': { opacity: '0', transform: 'translateX(-50px)' },
                        '100%': { opacity: '1', transform: 'translateX(0)' }
                    },
                    slideInRight: {
                        '0%': { opacity: '0', transform: 'translateX(50px)' },
                        '100%': { opacity: '1', transform: 'translateX(0)' }
                    },
                    float: {
                        '0%, 100%': { transform: 'translateY(0px)' },
                        '50%': { transform: 'translateY(-20px)' }
                    }
                }
            }
        }
    }
</script>
<!-- ESTILOS PRINCIPALES -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    body {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        color: #1e293b;
        background-color: var(--light-bg);
        scroll-behavior: smooth;
        transition: --primary-color 0.3s ease, --button-color 0.3s ease;
    }
    
    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
        width: 12px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 4px;
        transition: background 0.3s ease;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: var(--secondary-color);
    }
    
    /* Gradientes personalizados */
    .gradient-primary {
        background: var(--primary-color);
        transition: background 0.3s ease;
    }
    
    .gradient-hero {
        background: linear-gradient(135deg, var(--primary-color) 0.95, rgba(59, 130, 246, 0.85) 100%);
        transition: background 0.3s ease;
    }
    
    /* Efectos de hover mejorados */
    .card-hover {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-large);
    }
    
    .btn-primary {
        background: var(--button-color);
        transition: all 0.3s ease;
        box-shadow: var(--shadow-soft);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }
    
    /* Efecto parallax suave */
    .parallax {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    
    /* Loading spinner */
    .spinner {
        border: 3px solid #f3f4f6;
        border-top: 3px solid var(--primary-color);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Efectos de texto */
    .text-gradient {
        background: var(--gradient-2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        transition: background 0.3s ease;
    }
    
    /* Navegación sticky mejorada */
    .navbar-scroll {
        backdrop-filter: blur(20px);
        background: var(--primary-color) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        transition: background 0.3s ease;
    }
    
    /* Animación de documentos */
    .document-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(59, 130, 246, 0.1);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .document-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px) scale(1.02);
        box-shadow: var(--shadow-large);
    }
    
    /* Efecto de carga para imágenes */
    .img-loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Mejoras responsivas */
    @media (max-width: 768px) {
        .mobile-padding { padding: 1rem; }
        .mobile-text { font-size: 0.875rem; }
    }
    
    /* Indicadores de notificación */
    .notification-badge {
        position: relative;
    }
    
    .notification-badge::after {
        content: '';
        position: absolute;
        top: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    /* Efecto de typing para títulos importantes */
    .typing-effect {
        border-right: 2px solid var(--primary-color);
        animation: typing 3s steps(40) 1s 1 normal both, blink 1s infinite;
    }
    
    @keyframes typing {
        from { width: 0; }
        to { width: 100%; }
    }
    
    @keyframes blink {
        50% { border-color: transparent; }
    }

    /* Noticias Detalladas */
    .prose p {
        margin-bottom: 1.2rem;
        font-size: 1.05rem;
    }
    .prose strong {
        color: var(--primary-color);
        transition: color 0.3s ease;
    }
    .prose img {
        border-radius: 0.75rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    /* Animación de pulsado del botón */
    @keyframes pulse-click {
        0% { transform: scale(1); }
        50% { transform: scale(0.95); }
        100% { transform: scale(1); }
    }

    #copyLinkBtn:active {
        animation: pulse-click 0.3s ease-in-out;
    }
</style>    
        <style>
            /* Noticias Detalladas*/
            .prose p {
                margin-bottom: 1.2rem;
                font-size: 1.05rem;
            }
            .prose strong {
                color: #1e40af;
            }
            .prose img {
                border-radius: 0.75rem;
                box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            }

            /* Animación de pulsado del botón */
            @keyframes pulse-click {
                0% { transform: scale(1); }
                50% { transform: scale(0.95); }
                100% { transform: scale(1); }
            }

            #copyLinkBtn:active {
                animation: pulse-click 0.3s ease-in-out;
            }
            
        </style>



