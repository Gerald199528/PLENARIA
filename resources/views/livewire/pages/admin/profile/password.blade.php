<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

new class extends Component {

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';


    public function updatePassword()
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }
        
        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('swal', [
            'title' => '¡Exito!',
            'icon' => 'success',
            'text' => 'La contraseña se ha actualizado correctamente',
        ]);
        
    }
}; ?>


<div>
    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            
            <div class="relative mb-6 w-full">
                <h1 class="text-2xl font-bold">Contraseña</h1>
                <p class="text-sm text-gray-500">Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantenerla segura.</p>
                <hr class="my-4 border-gray-200">
            </div>
   <form wire:submit.prevent="updatePassword" class="my-4 sm:my-6 w-full space-y-4 sm:space-y-6">
                
                <!-- Contraseña Actual -->
                <div class="relative group">
                    <label for="current_password" class="flex items-center gap-1.5 sm:gap-2 mb-1 sm:mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="lock-closed" class="w-4 h-4 sm:w-5 sm:h-5" /> <span>Contraseña Actual</span>
                    </label>
                    <input type="password" id="current_password" wire:model="current_password" placeholder="Ingresa tu contraseña actual" required autofocus autocomplete="current-password"
                        class="block w-full p-2.5 sm:p-3 md:p-4 text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                </div>

                <!-- Nueva Contraseña -->
                <div class="relative group">
                    <label for="password" class="flex items-center gap-1.5 sm:gap-2 mb-1 sm:mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="lock-closed" class="w-4 h-4 sm:w-5 sm:h-5" /> <span>Nueva Contraseña</span>
                    </label>
                    <input type="password" id="password" wire:model="password" placeholder="Ingresa tu nueva contraseña" required autocomplete="new-password"
                        class="block w-full p-2.5 sm:p-3 md:p-4 text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                </div>

                <!-- Confirmar Contraseña -->
                <div class="relative group">
                    <label for="password_confirmation" class="flex items-center gap-1.5 sm:gap-2 mb-1 sm:mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="lock-closed" class="w-4 h-4 sm:w-5 sm:h-5" /> <span>Confirmar Contraseña</span>
                    </label>
                    <input type="password" id="password_confirmation" wire:model="password_confirmation" placeholder="Confirma tu nueva contraseña" required autocomplete="new-password"
                        class="block w-full p-2.5 sm:p-3 md:p-4 text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                </div>

                <!-- Validación de errores -->
                <x-validation-errors />

                <!-- Footer con botón -->
                <x-slot name="footer">
                    <div class="flex justify-center">
                        <x-button info icon="check" label="Guardar" wire:click="updatePassword" spinner="updatePassword"
                            class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 md:px-8 py-2 sm:py-3 md:py-3.5 text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl sm:rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 w-full sm:w-auto" />
                    </div>
                </x-slot>
            </form>
            
        </x-card>
    </x-container>
</div>