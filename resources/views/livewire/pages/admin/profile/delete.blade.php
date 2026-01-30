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
            <p class="text-sm sm:text-base text-red-500 font-medium">¿Estás seguro de querer eliminar tu cuenta? Esta acción es irreversible. Por favor, ingrese su contraseña actual para confirmar la eliminación.</p>
    

 <form wire:submit.prevent="deleteUser" class="my-4 sm:my-6 w-full space-y-4 sm:space-y-6">

                <!-- Contraseña -->
                <div class="relative group">
                    <label for="password" class="flex items-center gap-1.5 sm:gap-2 mb-1 sm:mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="lock-closed" class="w-4 h-4 sm:w-5 sm:h-5" /> <span>Contraseña</span>
                    </label>
                    <input type="password" id="password" wire:model="password" placeholder="Ingresa tu contraseña para confirmar" required autocomplete="current-password"
                        class="block w-full p-2.5 sm:p-3 md:p-4 text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                </div>

                <!-- Validación de errores -->
                <x-validation-errors />

                <!-- Footer con botones -->
                <x-slot name="footer">
                    <div class="flex flex-col-reverse sm:flex-row justify-center gap-2 sm:gap-3 pt-2 sm:pt-4">
                        <x-button slate icon="x-mark" label="Cancelar" x-on:click="close"
                            class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-3.5 text-xs sm:text-sm md:text-base bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 w-full sm:w-auto" />
                        
                        <x-button negative icon="trash" label="Eliminar cuenta" wire:click="deleteUser" spinner="deleteUser"
                            class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-3.5 text-xs sm:text-sm md:text-base bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold rounded-xl sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-red-500 hover:to-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 w-full sm:w-auto" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    
    </x-modal>
</div>