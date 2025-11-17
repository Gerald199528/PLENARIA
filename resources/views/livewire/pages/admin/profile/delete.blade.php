<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {

    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
     public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: false);
    }
}; ?>


<div>
    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            
            <div class="relative mb-6 w-full">
                <h1 class="text-2xl font-bold">Eliminar cuenta</h1>
                <p class="text-sm text-gray-500">Eliminar su cuenta es una acción irreversible.</p>
                <hr class="my-4 border-gray-200">
            </div>
<div class="flex justify-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <x-button negative icon="trash" label="Eliminar cuenta" x-on:click="$openModal('simpleModal')"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-red-500 hover:to-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400" />
            </div>
        </x-card>
    </x-container>

    <x-modal name="simpleModal">

        <x-card title="Eliminar cuenta">
            <p class="text-red-500">¿Estás seguro de querer eliminar tu cuenta? Esta acción es irreversible. Por favor, ingrese su contraseña actual para confirmar la eliminación.</p>
    

 <form wire:submit.prevent="deleteUser" class="my-6 w-full space-y-6">

                <!-- Contraseña -->
                <div class="relative group">
                    <label for="password" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="lock-closed" /> Contraseña
                    </label>
                    <input type="password" id="password" wire:model="password" placeholder="Ingresa tu contraseña para confirmar" required autocomplete="current-password"
                        class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                </div>

                <!-- Validación de errores -->
                <x-validation-errors />

                <!-- Footer con botones -->
                <x-slot name="footer">
                    <div class="flex justify-center gap-3">
                        <x-button slate icon="x-mark" label="Cancelar" x-on:click="close"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
                        
                        <x-button negative icon="trash" label="Eliminar cuenta" wire:click="deleteUser" spinner="deleteUser"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-red-500 hover:to-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    
    </x-modal>
</div>