<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
};
?>

<nav class="fixed top-0 z-50 w-full bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-700 animate-gradientBackground backdrop-blur-md border-b border-white/20 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 dark:border-gray-700 shadow-lg">
    <div class="px-3 sm:px-4 md:px-5 lg:px-6 py-2 sm:py-3 md:py-4">
        <div class="flex items-center justify-between w-full gap-2 sm:gap-3 md:gap-4">

            <!-- IZQUIERDA -->
            <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-shrink-0">

                <!-- BOTÓN MOBILE -->
                <button 
                    x-on:click="sidebarOpen = !sidebarOpen"
                    class="inline-flex items-center justify-center p-1.5 sm:p-2 text-white rounded-lg sm:hidden hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/40 transition-all duration-300"
                >
                    <i class="fa-solid fa-bars text-base sm:text-lg"></i>
                </button>

                <!-- LOGO RESPONSIVE -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 flex items-center justify-center rounded shadow-md animate-bounce-slow bg-white/20">
                        <i class="fas fa-landmark text-lg sm:text-2xl md:text-3xl text-white animate-bounce"></i>
                    </div>

                    <!-- TÍTULO -->
                    <span class="hidden sm:block font-bold text-white text-sm sm:text-base md:text-lg lg:text-xl group-hover:underline transition-all duration-300">
                        PLENARIA
                    </span>
                </a>

            </div>

            <!-- DERECHA -->
            <div class="flex items-center gap-1 sm:gap-2 md:gap-3 ml-auto flex-shrink-0">

                <livewire:components.teme-switcher />

                @if(Auth::check())
                <x-dropdown>
                    <x-slot name="trigger">
                        <span class="inline-flex rounded-md">
                            <x-button 
                                label="{{ Auth::user()->name }} {{ Auth::user()->last_name }}" 
                                flat 
                                white 
                                right-icon="chevron-down"
                                class="text-xs sm:text-sm hover:scale-105 transition-all duration-300 px-2 sm:px-3 md:px-4"
                            />
                        </span>
                    </x-slot>

                    <x-dropdown.header label="Configuración" />
                    <x-dropdown.item icon="user" label="Mi Perfil" :href="route('admin.profile.index')" wire:navigate />
                    <x-dropdown.item separator label="Cerrar Sesión" wire:click="logout" icon="arrow-right-start-on-rectangle" />
                </x-dropdown>
                @endif

            </div>

        </div>
    </div>
</nav>

@push('styles')
<style>
    /* Gradiente animado */
    @keyframes gradientBackground {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-gradientBackground {
        background-size: 200% 200%;
        animation: gradientBackground 15s ease infinite;
    }

    /* Animación del icono */
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        30% { transform: translateY(-5px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 2s ease-in-out infinite;
    }

    /* Mobile optimizado */
    @media (max-width: 640px) {
        nav {
            padding: 0.5rem 0.75rem;
        }
    }

    @media (max-width: 480px) {
        nav {
            padding: 0.5rem 0.5rem;
        }
    }

    /* Prevenir layout shift */
    nav button, nav a {
        will-change: transform;
    }

    /* Hover effects */
    @media (hover: hover) {
        button:hover {
            transform: scale(1.05);
        }
    }
</style>
@endpush