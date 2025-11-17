<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; 
?>

<nav class="fixed top-0 z-50 w-full bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-700 animate-gradientBackground backdrop-blur-md border-b border-white/20 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 dark:border-gray-700 shadow-lg">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button x-on:click="sidebarOpen = !sidebarOpen" data-drawer-target="logo-sidebar"
                    data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
                    class="inline-flex items-center p-2 text-sm text-white rounded-lg sm:hidden hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/40 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-gray-600 transition-all duration-300">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="{{ route('home') }}" class="flex ms-2 md:me-24 inline-flex items-center gap-3 group">
                <div class="w-10 h-10 flex items-center justify-center from-white to-gray-200 rounded font-bold text-white text-sm shadow-lg animate-bounce" style="animation-duration: 2s;">
                    <i class="fas fa-landmark text-3xl"></i>
                </div>  
                         <!-- Texto animado con brillo -->
                    <span class="text-2xl font-bold tracking-wider text-white group-hover:text-gray-100 transition-all duration-300">
                        PLENARIA
                    </span>
                </a>
            </div>

            <div class="flex items-center">
                <div class="flex items-center ms-3">

                    <livewire:components.teme-switcher />

                    @if(Auth::check())
                    <x-dropdown>
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                                <x-button label="{{ Auth::user()->name }} {{ Auth::user()->last_name }}" flat white
                                    right-icon="chevron-down" class="hover:scale-105 transition-all duration-300"/>
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
   /* icono animation */
   @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        30% { transform: translateY(-5px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 2s ease-in-out infinite;
    }
</style>
@endpush