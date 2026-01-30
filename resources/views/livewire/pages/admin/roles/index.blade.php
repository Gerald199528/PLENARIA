<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Roles');
    }

    public function deleteRole(Role $role)
    {
        // Validar si es el rol principal (ID 1)
        if ($role->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el rol principal del sistema',
            ]);
            return;
        }

        // Validar si tiene el rol Super Admin
        if ($role->name === 'Super Admin') {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar un rol con rol Super Admin',
            ]);
            return;
        }

        // Si pasa todas las validaciones, proceder con la eliminación
        try {
            $role->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Rol eliminado',
                'text' => 'El rol se ha eliminado correctamente',
            ]);

            // Recargar la tabla
            $this->dispatch('pg:eventRefresh-role-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el rol',
            ]);
        }
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
                'name' => 'Roles',
            ],
        ]" />
    </x-slot>

@can('create-role')
    <x-slot name="action">
        <a href="{{ route('admin.roles.create') }}" 
           wire:navigate
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
            <i class="fa-solid fa-plus animate-bounce"></i>
            Nuevo Rol
        </a>
    </x-slot>
@endcan
    <x-container class="w-full px-4">

        <livewire:role-table />

    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(role_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteRole', role_id);
                    }
                });
            }
        </script>
    @endpush
</div>
