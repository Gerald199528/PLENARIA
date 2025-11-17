<?php

use Livewire\Volt\Component;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Permisos');
    }
    
}; ?>

<div>

    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Permisos',
            ],
        ]" />
    </x-slot>
@can('create-permission')
    <x-slot name="action">
        <a href="{{ route('admin.permissions.create') }}" 
           wire:navigate
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
            <i class="fa-solid fa-plus animate-bounce"></i>
            Nuevo Permiso
        </a>
    </x-slot>
@endcan
    <x-container class="w-full px-4">

        <livewire:permission-table />

    </x-container>

    @push('scripts')
    @endpush
</div>
