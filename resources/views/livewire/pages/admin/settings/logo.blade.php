
<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

new class extends Component {
    use WithFileUploads;

    public $logo;
    public $logo_background_solid;
    public $logo_grey;
    public $logo_horizontal;
    public $logo_horizontal_background_solid;
    public $logo_icon;
    public $logo_icon_grey;

    public $temp_logo = null;
    public $temp_logo_background_solid = null;
    public $temp_logo_grey = null;
    public $temp_logo_horizontal = null;
    public $temp_logo_horizontal_background_solid = null;
    public $temp_logo_icon = null;
    public $temp_logo_icon_grey = null;

    protected $rules = [
        'temp_logo' => 'nullable|image|max:2048',
        'temp_logo_background_solid' => 'nullable|image|max:2048',
        'temp_logo_grey' => 'nullable|image|max:2048',
        'temp_logo_horizontal' => 'nullable|image|max:2048',
        'temp_logo_horizontal_background_solid' => 'nullable|image|max:2048',
        'temp_logo_icon' => 'nullable|image|max:2048',
        'temp_logo_icon_grey' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->logo = Setting::get('logo');
        $this->logo_background_solid = Setting::get('logo_background_solid');
        $this->logo_grey = Setting::get('logo_grey');
        $this->logo_horizontal = Setting::get('logo_horizontal');
        $this->logo_horizontal_background_solid = Setting::get('logo_horizontal_background_solid');
        $this->logo_icon = Setting::get('logo_icon');
        $this->logo_icon_grey = Setting::get('logo_icon_grey');
    }

    public function updatedTempLogo()
    {
        $this->validate(['temp_logo' => 'image|max:2048']);
    }

    public function updatedTempLogoBackgroundSolid()
    {
        $this->validate(['temp_logo_background_solid' => 'image|max:2048']);
    }

    public function updatedTempLogoGrey()
    {
        $this->validate(['temp_logo_grey' => 'image|max:2048']);
    }

    public function updatedTempLogoHorizontal()
    {
        $this->validate(['temp_logo_horizontal' => 'image|max:2048']);
    }

    public function updatedTempLogoHorizontalBackgroundSolid()
    {
        $this->validate(['temp_logo_horizontal_background_solid' => 'image|max:2048']);
    }

    public function updatedTempLogoIcon()
    {
        $this->validate(['temp_logo_icon' => 'image|max:2048']);
    }

    public function updatedTempLogoIconGrey()
    {
        $this->validate(['temp_logo_icon_grey' => 'image|max:2048']);
    }

    protected function getDefaultFilename($type)
    {
        $defaults = [
            'logo' => '1_logo',
            'logo_background_solid' => '2_logo_background_solid',
            'logo_grey' => '3_logo_grey',
            'logo_horizontal' => '4_logo_horizontal',
            'logo_horizontal_background_solid' => '5_logo_horizontal_background_solid',
            'logo_icon' => '6_logo_icon',
            'logo_icon_grey' => '7_logo_icon_grey'
        ];

        return $defaults[$type] ?? $type;
    }

    public function saveLogo($type)
    {
        $tempProperty = "temp_" . $type;
        $property = $type;
        
        if (!$this->{$tempProperty}) {
            return;
        }

        try {
            $this->validate([$tempProperty => 'image|max:2048']);

            $extension = $this->{$tempProperty}->getClientOriginalExtension();
            $filename = $this->getDefaultFilename($type);
            $path = "images/{$filename}.{$extension}";

            if ($this->{$property}) {
                Storage::disk('public')->delete($this->{$property});
            }

            $this->{$tempProperty}->storeAs('', $path, 'public');
            
            Setting::set($property, $path);
            $this->{$property} = $path;
            $this->{$tempProperty} = null;

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'El logo se ha actualizado correctamente',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar el logo: ' . $e->getMessage(),
            ]);
        }
    }
}; ?>



<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
            ['name' => 'Logos'],
        ]" />
    </x-slot>
<div class="w-full px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 transition-colors duration-200">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8">Gestión de Logos de la Empresa</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <!--Logo de la Empresa-->
            <div class="group relative bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl p-6 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-star text-yellow-500"></i>
                        Logo de la Empresa
                    </h3>
                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 text-xs font-semibold rounded-full">Principal</span>
                </div>
                
                <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 rounded">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Carga aquí los logos de la institución (escudo, bandera, logotipo, etc.)
                    </p>
                </div>
                
                <div class="relative overflow-hidden rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 transition-all duration-300 group-hover:border-blue-500 dark:group-hover:border-blue-400">
                    <!-- Overlay de carga -->
                    <div wire:loading wire:target="temp_logo" class="absolute inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-20 backdrop-blur-sm">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-white font-semibold text-sm">Cargando...</span>
                        </div>
                    </div>

                    <!-- Botón de actualizar -->
                    <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <label class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg cursor-pointer shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-camera"></i>
                            <span class="text-sm font-medium">Cambiar</span>
                            <input type="file" wire:model="temp_logo" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <!-- Imagen -->
                    <div class="aspect-video flex items-center justify-center p-4">
                        @if($temp_logo)
                            <img src="{{ $temp_logo->temporaryUrl() }}"
                                alt="Logo Principal (Vista Previa)"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @else
                            <img src="{{ $logo ? Storage::url($logo) : Storage::url('images/placeholder.png') }}"
                                alt="Logo Principal"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @endif
                    </div>
                </div>

                @if($temp_logo)
                    <div class="mt-4 flex gap-2 animate-fadeIn">
                        <button wire:click="saveLogo('logo')" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-blue-500 dark:bg-blue-600 text-white rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700 transition-colors duration-200 font-medium">
                            <i class="fas fa-check"></i>
                            <span wire:loading.remove wire:target="saveLogo">Guardar</span>
                            <span wire:loading wire:target="saveLogo">Guardando...</span>
                        </button>
                        <button wire:click="$set('temp_logo', null)" class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-200 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>

            <!--Eslogan Web Principalo -->
            <div class="group relative bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-fill-drip text-purple-500"></i>
                       Eslogan Web PrincipaL
                    </h3>
                </div>
                
                <div class="mb-3 p-3 bg-purple-50 dark:bg-purple-900/30 border-l-4 border-purple-500 rounded">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-info-circle text-purple-500 mr-2"></i>
                    Implantado en la trasparencia de la pagina web.
                    </p>
                </div>
                
                <div class="relative overflow-hidden rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 transition-all duration-300 group-hover:border-purple-500 dark:group-hover:border-purple-400">
                    <div wire:loading wire:target="temp_logo_background_solid" class="absolute inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-20 backdrop-blur-sm">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-white font-semibold text-sm">Procesando...</span>
                        </div>
                    </div>

                    <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <label class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg cursor-pointer shadow-lg hover:shadow-xl hover:from-purple-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-camera"></i>
                            <span class="text-sm font-medium">Cambiar</span>
                            <input type="file" wire:model="temp_logo_background_solid" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div class="aspect-video flex items-center justify-center p-4">
                        @if($temp_logo_background_solid)
                            <img src="{{ $temp_logo_background_solid->temporaryUrl() }}"
                                alt="Logo con Fondo Sólido (Vista Previa)"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @else
                            <img src="{{ $logo_background_solid ? Storage::url($logo_background_solid) : Storage::url('images/placeholder.png') }}"
                                alt="Logo con Fondo Sólido"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @endif
                    </div>
                </div>

                @if($temp_logo_background_solid)
                    <div class="mt-4 flex gap-2 animate-fadeIn">
                        <button wire:click="saveLogo('logo_background_solid')" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-purple-500 dark:bg-purple-600 text-white rounded-lg hover:bg-purple-600 dark:hover:bg-purple-700 transition-colors duration-200 font-medium">
                            <i class="fas fa-check"></i>
                            <span wire:loading.remove wire:target="saveLogo">Guardar</span>
                            <span wire:loading wire:target="saveLogo">Guardando...</span>
                        </button>
                        <button wire:click="$set('temp_logo_background_solid', null)" class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-200 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>
                   
            <!--   Icono principal  de la App-->
            <div class="group relative bg-gradient-to-br from-pink-50 to-rose-100 dark:from-pink-900/30 dark:to-rose-800/30 rounded-2xl p-6 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-icons text-pink-500"></i>
                        Icono principal del "Sistema"
                    </h3>
                </div>
                
                <div class="mb-3 p-3 bg-pink-50 dark:bg-pink-900/30 border-l-4 border-pink-500 rounded">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-info-circle text-pink-500 mr-2"></i>
                        Carga un icono o elemento que identifique a la institución
                    </p>
                </div>
                
                <div class="relative overflow-hidden rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 transition-all duration-300 group-hover:border-pink-500 dark:group-hover:border-pink-400">
                    <div wire:loading wire:target="temp_logo_icon" class="absolute inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-20 backdrop-blur-sm">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-white font-semibold text-sm">Procesando...</span>
                        </div>
                    </div>

                    <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <label class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-lg cursor-pointer shadow-lg hover:shadow-xl hover:from-pink-600 hover:to-pink-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-camera"></i>
                            <span class="text-sm font-medium">Cambiar</span>
                            <input type="file" wire:model="temp_logo_icon" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div class="aspect-video flex items-center justify-center p-4">
                        @if($temp_logo_icon)
                            <img src="{{ $temp_logo_icon->temporaryUrl() }}"
                                alt="Logo Icono (Vista Previa)"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @else
                            <img src="{{ $logo_icon ? Storage::url($logo_icon) : Storage::url('images/placeholder.png') }}"
                                alt="Logo Icono"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @endif
                    </div>
                </div>

              @if($temp_logo_icon)
                    <div class="mt-4 flex gap-2 animate-fadeIn">
                        <button wire:click="saveLogo('logo_icon')" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-pink-500 dark:bg-pink-600 text-white rounded-lg hover:bg-pink-600 dark:hover:bg-pink-700 transition-colors duration-200 font-medium">
                            <i class="fas fa-check"></i>
                            <span wire:loading.remove wire:target="saveLogo">Guardar</span>
                            <span wire:loading wire:target="saveLogo">Guardando...</span>
                        </button>
                        <button wire:click="$set('temp_logo_icon', null)" class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-200 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>

             <!-- Logo Horizontal (Formato PDF) -->             
            <div class="group relative bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/30 dark:to-emerald-800/30 rounded-2xl p-6 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-arrows-alt-h text-green-500"></i>
                        Logo Horizontal "Formato PDF"
                    </h3>
                </div>
                   <div class="mb-3 p-3 bg-blue-50 dark:bg-green-900/30 border-l-4 border-green-500 rounded">
                    <p class="text-sm text-green-700 dark:text-green-300">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>
                        Este logo es para el formato de los PDF impelemntado en el sistema
                    </p>
                </div>
                
                <div class="relative overflow-hidden rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 transition-all duration-300 group-hover:border-green-500 dark:group-hover:border-green-400">
                    <div wire:loading wire:target="temp_logo_horizontal" class="absolute inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-20 backdrop-blur-sm">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-white font-semibold text-sm">Procesando...</span>
                        </div>
                    </div>

                    <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <label class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg cursor-pointer shadow-lg hover:shadow-xl hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-camera"></i>
                            <span class="text-sm font-medium">Cambiar</span>
                            <input type="file" wire:model="temp_logo_horizontal" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div class="aspect-video flex items-center justify-center p-4">
                        @if($temp_logo_horizontal)
                            <img src="{{ $temp_logo_horizontal->temporaryUrl() }}"
                                alt="Logo Horizontal (Vista Previa)"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @else
                            <img src="{{ $logo_horizontal ? Storage::url($logo_horizontal) : Storage::url('images/placeholder.png') }}"
                                alt="Logo Horizontal"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @endif
                    </div>
                </div>

                @if($temp_logo_horizontal)
                    <div class="mt-4 flex gap-2 animate-fadeIn">
                        <button wire:click="saveLogo('logo_horizontal')" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-green-500 dark:bg-green-600 text-white rounded-lg hover:bg-green-600 dark:hover:bg-green-700 transition-colors duration-200 font-medium">
                            <i class="fas fa-check"></i>
                            <span wire:loading.remove wire:target="saveLogo">Guardar</span>
                            <span wire:loading wire:target="saveLogo">Guardando...</span>
                        </button>
                        <button wire:click="$set('temp_logo_horizontal', null)" class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-200 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>

            <!-- L Logo Plenaria  -->
            <div class="group relative bg-gradient-to-br from-orange-50 to-amber-100 dark:from-orange-900/30 dark:to-amber-800/30 rounded-2xl p-6 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-palette text-orange-500"></i>
                       Logo  Plenaria "login, Dashboard" 
                    </h3>
                </div>
                    <div class="mb-3 p-3 bg-blue-50 dark:bg-orange-900/30 border-l-4 border-orange-500 rounded">
                    <p class="text-sm text-orange-700 dark:text-orange-300">
                        <i class="fas fa-info-circle text-orange-500 mr-2"></i>
                       Logo principal "PLENARIA" Implantado en (Login, Dashboard)
                    </p>
                </div>
                
                <div class="relative overflow-hidden rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 transition-all duration-300 group-hover:border-orange-500 dark:group-hover:border-orange-400">
                    <div wire:loading wire:target="temp_logo_horizontal_background_solid" class="absolute inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-20 backdrop-blur-sm">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-white font-semibold text-sm">Cargando...</span>
                        </div>
                    </div>

                    <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <label class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg cursor-pointer shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-camera"></i>
                            <span class="text-sm font-medium">Cambiar</span>
                            <input type="file" wire:model="temp_logo_horizontal_background_solid" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div class="aspect-video flex items-center justify-center p-4">
                        @if($temp_logo_horizontal_background_solid)
                            <img src="{{ $temp_logo_horizontal_background_solid->temporaryUrl() }}"
                                alt="Logo Horizontal con Fondo Sólido (Vista Previa)"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @else
                            <img src="{{ $logo_horizontal_background_solid ? Storage::url($logo_horizontal_background_solid) : Storage::url('images/placeholder.png') }}"
                                alt="Logo Horizontal con Fondo Sólido"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @endif
                    </div>
                </div>

                @if($temp_logo_horizontal_background_solid)
                    <div class="mt-4 flex gap-2 animate-fadeIn">
                        <button wire:click="saveLogo('logo_horizontal_background_solid')" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-orange-500 dark:bg-orange-600 text-white rounded-lg hover:bg-orange-600 dark:hover:bg-orange-700 transition-colors duration-200 font-medium">
                            <i class="fas fa-check"></i>
                            <span wire:loading.remove wire:target="saveLogo">Guardar</span>
                            <span wire:loading wire:target="saveLogo">Guardando...</span>
                        </button>
                        <button wire:click="$set('temp_logo_horizontal_background_solid', null)" class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-200 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>

             <!--Escala de Grises -->
            <div class="group relative bg-gradient-to-br from-gray-50 to-slate-100 dark:from-gray-700 dark:to-slate-800 rounded-2xl p-6 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-adjust text-gray-500 dark:text-gray-400"></i>
                        Escala de Grises
                    </h3>
                </div>
                
                <div class="relative overflow-hidden rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 transition-all duration-300 group-hover:border-gray-500 dark:group-hover:border-gray-400">
                    <div wire:loading wire:target="temp_logo_grey" class="absolute inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-20 backdrop-blur-sm">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-white font-semibold text-sm">Cargando...</span>
                        </div>
                    </div>

                    <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <label class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg cursor-pointer shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-camera"></i>
                            <span class="text-sm font-medium">Cambiar</span>
                            <input type="file" wire:model="temp_logo_grey" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div class="aspect-video flex items-center justify-center p-4">
                        @if($temp_logo_grey)
                            <img src="{{ $temp_logo_grey->temporaryUrl() }}"
                                alt="Logo Escala de Grises (Vista Previa)"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110 grayscale">
                        @else
                            <img src="{{ $logo_grey ? Storage::url($logo_grey) : Storage::url('images/placeholder.png') }}"
                                alt="Logo Escala de Grises"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110 grayscale">
                        @endif
                    </div>
                </div>
                     @if($temp_logo_grey)
                    <div class="mt-4 flex gap-2 animate-fadeIn">
                        <button wire:click="saveLogo('logo_grey')" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-gray-500 dark:bg-gray-600 text-white rounded-lg hover:bg-gray-600 dark:hover:bg-gray-700 transition-colors duration-200 font-medium">
                            <i class="fas fa-check"></i>
                            <span wire:loading.remove wire:target="saveLogo">Guardar</span>
                            <span wire:loading wire:target="saveLogo">Guardando...</span>
                        </button>
                        <button wire:click="$set('temp_logo_grey', null)" class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-200 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

          
            </div>

            <!-- Logo Icono Gris -->
            <div class="group relative bg-gradient-to-br from-indigo-50 to-blue-100 dark:from-indigo-900/30 dark:to-blue-800/30 rounded-2xl p-6 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-circle text-indigo-500"></i>
                        Icono Gris
                    </h3>
                </div>
                
                <div class="relative overflow-hidden rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 transition-all duration-300 group-hover:border-indigo-500 dark:group-hover:border-indigo-400">
                    <div wire:loading wire:target="temp_logo_icon_grey" class="absolute inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-20 backdrop-blur-sm">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-white font-semibold text-sm">Cargando...</span>
                        </div>
                    </div>

                    <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <label class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg cursor-pointer shadow-lg hover:shadow-xl hover:from-indigo-600 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-camera"></i>
                            <span class="text-sm font-medium">Cambiar</span>
                            <input type="file" wire:model="temp_logo_icon_grey" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div class="aspect-video flex items-center justify-center p-4">
                        @if($temp_logo_icon_grey)
                            <img src="{{ $temp_logo_icon_grey->temporaryUrl() }}"
                                alt="Logo Icono Gris (Vista Previa)"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110 grayscale">
                        @else
                            <img src="{{ $logo_icon_grey ? Storage::url($logo_icon_grey) : Storage::url('images/placeholder.png') }}"
                                alt="Logo Icono Gris"
                                class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110 grayscale">
                        @endif
                    </div>
                </div>

                @if($temp_logo_icon_grey)
                    <div class="mt-4 flex gap-2 animate-fadeIn">
                        <button wire:click="saveLogo('logo_icon_grey')" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-indigo-500 dark:bg-indigo-600 text-white rounded-lg hover:bg-indigo-600 dark:hover:bg-indigo-700 transition-colors duration-200 font-medium">
                            <i class="fas fa-check"></i>
                            <span wire:loading.remove wire:target="saveLogo">Guardar</span>
                            <span wire:loading wire:target="saveLogo">Guardando...</span>
                        </button>
                        <button wire:click="$set('temp_logo_icon_grey', null)" class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-200 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>