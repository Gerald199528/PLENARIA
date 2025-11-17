<?php

use Livewire\Volt\Component;
use App\Models\Setting;
use Illuminate\View\View;

new class extends Component {
    public $primaryColor = '';
    public $secondaryColor = '';
    public $buttonColor = '';

    public function mount()
    {     
        $this->primaryColor = Setting::get('primary_color', '#1d4ed8');
        $this->secondaryColor = Setting::get('secondary_color', '#3b82f6');
        $this->buttonColor = Setting::get('button_color', '#1d4ed8');
    }

    public function saveColors()    
    {
        $this->validate([
            'primaryColor' => 'required|string',
            'secondaryColor' => 'required|string',
            'buttonColor' => 'required|string',
        ], [
            'primaryColor.required' => 'El color primario es requerido',
            'secondaryColor.required' => 'El color secundario es requerido',
            'buttonColor.required' => 'El color de botones es requerido',
        ]);

        try {           
            $primarySetting = Setting::firstOrCreate(
                ['key' => 'primary_color'],
                [
                    'value' => $this->primaryColor,
                    'type' => 'string',
                    'group' => 'colors',
                    'name' => 'Color Primario',
                    'description' => 'Color primario de la aplicación',
                    'is_public' => true,
                    'is_tenant_editable' => true,
                ]
            );
            $primarySetting->update(['value' => $this->primaryColor]);

            $secondarySetting = Setting::firstOrCreate(
                ['key' => 'secondary_color'],
                [
                    'value' => $this->secondaryColor,
                    'type' => 'string',
                    'group' => 'colors',
                    'name' => 'Color Secundario',
                    'description' => 'Color secundario para gradientes',
                    'is_public' => true,
                    'is_tenant_editable' => true,
                ]
            );
            $secondarySetting->update(['value' => $this->secondaryColor]);

            $buttonSetting = Setting::firstOrCreate(
                ['key' => 'button_color'],
                [
                    'value' => $this->buttonColor,
                    'type' => 'string',
                    'group' => 'colors',
                    'name' => 'Color de Botones',
                    'description' => 'Color de todos los botones',
                    'is_public' => true,
                    'is_tenant_editable' => true,
                ]
            );
            $buttonSetting->update(['value' => $this->buttonColor]);

            // ⭐ Emitir evento para actualizar colores en TODA la página
            $this->dispatch('colorsUpdated', 
                primaryColor: $this->primaryColor,
                secondaryColor: $this->secondaryColor,
                buttonColor: $this->buttonColor
            );

            $this->dispatch('swal', [
                'title' => '¡Éxito!',
                'icon' => 'success',
                'text' => 'Los colores se han guardado correctamente',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error',
                'icon' => 'error',
                'text' => 'Error al guardar los colores: ' . $e->getMessage(),
            ]);
        }
    }
        public function limpiar($showAlert = true)
        {
            $coloresIguales = $this->primaryColor === '#1d4ed8' && 
                            $this->secondaryColor === '#3b82f6' && 
                            $this->buttonColor === '#1d4ed8';
            
            if ($coloresIguales) {
                if ($showAlert) {
                    $this->dispatch('showAlert', [
                        'icon' => 'info',
                        'title' => 'Oops',
                        'text' => 'Los colores ya están en los valores por defecto.',
                        'timer' => 2000,
                        'timerProgressBar' => true,
                        'toast' => true,
                        'position' => 'top-end',
                        'showConfirmButton' => false,
                    ]);
                }
                return;
            }
            
            $this->primaryColor = '#1d4ed8';
            $this->secondaryColor = '#3b82f6';
            $this->buttonColor = '#1d4ed8';
            
            if ($showAlert) {
                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => 'Colores reiniciados',
                    'text' => 'Los colores han sido restaurados a los valores por defecto.',
                    'timer' => 2000,
                    'timerProgressBar' => true,
                    'toast' => true,
                    'position' => 'top-end',
                    'showConfirmButton' => false,
                ]);
            }
        }
}; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Configuración'],
            ['name' => 'Colores'],
        ]" />
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">🎨 Configuración de Colores</h1>
            <p class="text-xl text-gray-600 dark:text-gray-300">Personaliza los colores principales de tu aplicación</p>
        </div>

        <form wire:submit.prevent="saveColors" class="space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                <!-- Color Primario -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur-2xl opacity-0 group-hover:opacity-20 transition-opacity duration-500"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl bg-opacity-95">
                        <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                        
                        <div class="p-10">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-xl">🎯</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Color Primario</h3>
                                    <p class="text-sm text-gray-500">Headers y elementos</p>
                                </div>
                            </div>

                            <div class="mb-8">
                                <x-color-picker 
                                    wire:model.live="primaryColor"
                                    label="Selecciona el color"
                                    placeholder="Color primario"
                                />
                            </div>

                            <div class="space-y-4">
                                <div class="relative h-40 rounded-xl overflow-hidden shadow-lg transform transition-all duration-300 hover:scale-105"
                                    style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }}dd 100%);">
                                    <div class="absolute inset-0 opacity-0 hover:opacity-10 bg-white transition-opacity duration-300"></div>
                                </div>
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 text-center">
                                    <p class="text-xs text-gray-600 font-semibold uppercase tracking-wider mb-2">Código Hex</p>
                                    <p class="text-3xl font-bold text-gray-900 font-mono">{{ $primaryColor }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Color Secundario -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl blur-2xl opacity-0 group-hover:opacity-20 transition-opacity duration-500"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl bg-opacity-95">
                        <div class="h-2 bg-gradient-to-r from-purple-500 to-pink-600"></div>
                        
                        <div class="p-10">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-xl">🌈</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Color Secundario</h3>
                                    <p class="text-sm text-gray-500">Gradientes y detalles</p>
                                </div>
                            </div>

                            <div class="mb-8">
                                <x-color-picker 
                                    wire:model.live="secondaryColor"
                                    label="Selecciona el color"
                                    placeholder="Color secundario"
                                />
                            </div>

                            <div class="space-y-4">
                                <div class="relative h-40 rounded-xl overflow-hidden shadow-lg transform transition-all duration-300 hover:scale-105"
                                    style="background: linear-gradient(135deg, {{ $secondaryColor }} 0%, {{ $secondaryColor }}dd 100%);">
                                    <div class="absolute inset-0 opacity-0 hover:opacity-10 bg-white transition-opacity duration-300"></div>
                                </div>
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 text-center">
                                    <p class="text-xs text-gray-600 font-semibold uppercase tracking-wider mb-2">Código Hex</p>
                                    <p class="text-3xl font-bold text-gray-900 font-mono">{{ $secondaryColor }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Color Botones -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl blur-2xl opacity-0 group-hover:opacity-20 transition-opacity duration-500"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl bg-opacity-95">
                        <div class="h-2 bg-gradient-to-r from-indigo-500 to-indigo-600"></div>
                        
                        <div class="p-10">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-xl">🔘</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Color Botones</h3>
                                    <p class="text-sm text-gray-500">Todos los botones</p>
                                </div>
                            </div>

                            <div class="mb-8">
                                <x-color-picker 
                                    wire:model.live="buttonColor"
                                    label="Selecciona el color"
                                    placeholder="Color de botones"
                                />
                            </div>

                            <div class="space-y-4">
                                <div class="relative h-40 rounded-xl overflow-hidden shadow-lg transform transition-all duration-300 hover:scale-105"
                                    style="background: linear-gradient(135deg, {{ $buttonColor }} 0%, {{ $buttonColor }}dd 100%);">
                                    <div class="absolute inset-0 opacity-0 hover:opacity-10 bg-white transition-opacity duration-300"></div>
                                </div>
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 text-center">
                                    <p class="text-xs text-gray-600 font-semibold uppercase tracking-wider mb-2">Código Hex</p>
                                    <p class="text-3xl font-bold text-gray-900 font-mono">{{ $buttonColor }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
            
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900 dark:to-indigo-900 rounded-2xl border-2 border-blue-200 dark:border-blue-700 p-8 shadow-lg">
                <div class="relative">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl animate-bounce">💡</span>
                        <p class="text-lg text-gray-800 dark:text-gray-100">
                            <strong>Tip:</strong> Los cambios se aplicarán en toda la aplicación instantáneamente.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-center gap-6 pt-6">
                <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                    class="inline-flex items-center gap-2 px-10 py-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold text-lg rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" />
                
                <x-button info type="submit" spinner="saveColors" label="Guardar Colores" icon="check"
                    class="inline-flex items-center gap-2 px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-lg rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl" />
            </div>
        </form>
    </div>
</div>