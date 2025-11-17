<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $photo;
    public $image_url;

    public function mount()
    {
        $this->image_url = Auth::user()->image_url;
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);
    }

    public function clearPhoto()
    {
        $this->photo = null;
    }

    public function updateProfilePhoto()
    {
        $this->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);
        
        if ($this->photo) {
            if ($this->image_url && !str_contains($this->image_url, 'no_user_image.png')) {
                Storage::disk('public')->delete($this->image_url);
            }

            $this->image_url = $this->photo->store('images/users', 'public');

            Auth::user()->update([
                'image_url' => $this->image_url,
            ]);

            $this->dispatch('swal', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'text' => 'La imagen de perfil se ha actualizado correctamente',
            ]);

            $this->photo = null;
        }
    }
}; ?>

<div>
    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <div class="relative mb-6 w-full">
                <h1 class="text-2xl font-bold">Imagen de Perfil</h1>
                <p class="text-sm text-gray-500">Por favor, seleccione una imagen para el usuario.</p>
                <hr class="my-4 border-gray-200">
            </div>
            
            <form wire:submit.prevent="updateProfilePhoto" class="my-6 w-full space-y-6">
                <figure class="mb-4 mt-4 relative text-black flex flex-col items-center">
                    <div class="absolute top-8 right-8">
                        <label class="text-black flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer shadow-lg">
                            <i class="fas fa-camera mr-2"></i> Actualizar Imagen
                            <input type="file" wire:model="photo" class="hidden" accept="image/*">
                        </label>
                    </div>
                    <img src="{{ $photo ? $photo->temporaryUrl() : (Storage::url($image_url ?: 'images/no_user_image.png')) }}"
                        alt="Imagen de usuario"
                        class="h-64 w-64 object-cover object-center rounded-lg">
                </figure>

                <div wire:loading wire:target="photo" class="flex justify-center mt-4">
                    <div class="flex items-center justify-center gap-3 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg w-full max-w-xs">
                        <div class="animate-spin rounded-full h-8 w-8 border-4 border-blue-200 border-b-2 border-b-blue-600"></div>
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-200">Cargando imagen...</span>
                    </div>
                </div>

                <x-validation-errors />
                
                <x-slot name="footer">
                    <div class="flex justify-center">
                        <x-button info icon="check" label="Guardar" wire:click="updateProfilePhoto" spinner="updateProfilePhoto"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>