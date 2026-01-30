<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>


<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Solicitudes Pendientes'],
        ]" />
    </x-slot>
    <x-container class="w-full px-4 mt-6">
        <livewire:solicitudTable />
    </x-container>
</div>
