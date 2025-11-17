<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

new class extends Component {

    public $name;
    public $last_name;
    public $phone;
    public $email;

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->last_name = Auth::user()->last_name;
        $this->phone = Auth::user()->phone;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::user()->id,
        ]);
        
        $user = Auth::user();
        $user->name = $this->name;
        $user->last_name = $this->last_name;
        $user->phone = $this->phone;
        $user->email = $this->email;
        $user->save();

        $this->dispatch('swal', [
            'title' => '¡Exito!',
            'icon' => 'success',
            'text' => 'El perfil se ha actualizado correctamente',
        ]);
        
    }
}; ?>


<div>
    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            
            <div class="relative mb-6 w-full">
                <h1 class="text-2xl font-bold">Mi perfil</h1>
                <p class="text-sm text-gray-500">Actualiza tu nombre y correo electrónico.</p>
                <hr class="my-4 border-gray-200">
            </div>
   <form wire:submit.prevent="updateProfileInformation" class="my-6 w-full space-y-6">
                
                <!-- Nombre -->
                <div class="relative group">
                    <label for="name" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="user" /> Nombre
                    </label>
                    <input type="text" id="name" wire:model="name" placeholder="Ej: Juan" required autofocus autocomplete="name"
                        class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                </div>

                <!-- Apellido -->
                <div class="relative group">
                    <label for="last_name" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="user" /> Apellido
                    </label>
                    <input type="text" id="last_name" wire:model="last_name" placeholder="Ej: Ramírez" required autocomplete="last_name"
                        class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                </div>

                <!-- Teléfono -->
                <div class="relative group" x-data>
                    <label for="phone" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="phone" /> Teléfono
                    </label>
                    <div class="flex transform transition-all duration-300 group-hover:scale-[1.02]">
                        <span class="inline-flex items-center px-3 rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold">
                            +58
                        </span>
                        <input type="text" id="phone" wire:model="phone" placeholder="Ej: 04129766844" required autocomplete="phone"
                            x-on:input="$el.value = $el.value.replace(/[^0-9]/g,''); if($el.value.length > 11) { $el.value = $el.value.slice(0,11) } @this.set('phone', $el.value);"
                            maxlength="11"
                            class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-r-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300">
                    </div>
                </div>

                <!-- Correo Electrónico -->
                <div class="relative group">
                    <label for="email" class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
                        <x-icon name="envelope" /> Correo Electrónico
                    </label>
                    <input type="email" id="email" wire:model="email" placeholder="Ej: correo@example.com" required autocomplete="email"
                        class="block w-full p-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                </div>

                <!-- Validación de errores -->
                <x-validation-errors />
                
                <!-- Footer con botón -->
                <x-slot name="footer">
                    <div class="flex justify-center">
                        <x-button info icon="check" label="Guardar" wire:click="updateProfileInformation" spinner="updateProfileInformation"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400" />
                    </div>
                </x-slot>
            </form>
            
        </x-card>
    </x-container>
</div>