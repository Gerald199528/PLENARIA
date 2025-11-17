<div class="w-full min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 p-4">
            <div class="w-full max-w-7xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500">

                {{-- Header --}}
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-700 to-indigo-900 text-white">
                    <div class="flex items-center gap-3">
                <i class="fa-solid fa-building text-3xl text-white animate-bounce"></i>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-wide">
                            {{ $mode === 'create' ? 'Registrar Empresa' : 'Editar Empresa' }}
                        </h2>
                    </div>
                    <x-button slate wire:click="cancel" spinner="cancel" label="Volver" icon="arrow-left"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:scale-105" />
                </div>
                {{-- Información --}}
                <div class="p-4 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400 rounded-b-3xl">
                    <p class="text-blue-700 dark:text-blue-200 text-sm md:text-base flex items-center gap-2 animate-fadeIn">
                        <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-300"></i>
                        Completa la información de la empresa. Los campos con * son obligatorios.
                    </p>
                </div>
                {{-- Formulario --}}
                <form wire:submit.prevent="save" enctype="multipart/form-data" class="p-8 space-y-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Nombre -->
                        <div class="group">
                            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-building-office w-4 h-4"></i> Nombre de la empresa *
                            </label>
                            <input type="text" wire:model="name" placeholder="Ej: Mi Empresa C.A."
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg" />
                        </div>

                        <!-- Razón Social -->
                        <div class="group">
                            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-briefcase w-4 h-4"></i> Razón social *
                            </label>
                            <input type="text" wire:model="razon_social" placeholder="Ej: Soluciones Tecnológicas Avanzadas, C.A."
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg" />
                        </div>

                        <!-- RIF / Documento -->
                        <div class="group">
                            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-id-card w-4 h-4"></i> RIF *
                            </label>
                        <div class="relative group flex items-center gap-0">
                            <!-- Select letra inicial -->
                            <select wire:model="rif_letter"
                                class="p-4 rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg">
                                <option value="J">J</option>
                                <option value="G">G</option>
                                <option value="C">C</option>
                                <option value="V">V</option>
                                <option value="E">E</option>
                            </select>

                            <!-- Input número del documento -->
                            <input type="text" wire:model="rif_number" placeholder="123456789" maxlength="9"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-r-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 transform hover:scale-[1.01] group-hover:shadow-lg" />
                        </div>
                        </div>

                        <!-- Dirección Fiscal -->
                        <div class="group">
                            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-map-pin w-4 h-4"></i> Dirección fiscal *
                            </label>
                            <div class="flex gap-2">
                                <input type="text" wire:model="direccion_fiscal" placeholder="Ej: Av. Principal, C.C. Los Pinos, Piso 2, Oficina 3"
                                    class="block flex-1 p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg" />
                                
                           <button type="button" 
        onclick="openMapModal(); return false;" 
        class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-2xl whitespace-nowrap transition-all">
<i class="fa-solid fa-map animate-pulse"></i> Ver Mapa
</button>
                            </div>
                        </div>

                        @include('livewire.pages.admin.empresa.modal.map')

                        <!-- Oficina Principal -->
                        <div class="group">
                            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-house w-4 h-4"></i> Oficina principal *
                            </label>
                            <input type="text" wire:model="oficina_principal" placeholder="Ej: Sede Central"
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg" />
                        </div>

                        <!-- Horario de Atención -->
                        <div class="group">
                            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-clock w-4 h-4"></i> Horario de atención *
                            </label>
                            <input type="text" wire:model="horario_atencion" placeholder="Ej: Lunes a Viernes de 8am a 5pm"
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg" />
                        </div>

                        <!-- Teléfono Principal -->
                        <div class="relative group">
                            <label class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-phone"></i> Teléfono Principal *
                            </label>
                            <div class="flex transform transition-all duration-300 group-hover:scale-[1.02] focus-within:scale-[1.03] hover:shadow-lg rounded-2xl overflow-hidden">
                                <span class="inline-flex items-center px-3 rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold text-base transition-all duration-300 group-hover:bg-gray-300 dark:group-hover:bg-gray-600">
                                    +58
                                </span>
                                <input type="text" 
                                    wire:model.live="telefono_principal" 
                                    placeholder="4123456789" 
                                    maxlength="11"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 rounded-r-2xl transform hover:scale-[1.01]" />
                            </div>  
                        </div>

                        <!-- Teléfono Secundario -->
                        <div class="relative group">
                            <label class="flex items-center gap-2 mb-1 text-base font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-phone"></i> Teléfono Secundario *
                            </label>
                            <div class="flex transform transition-all duration-300 group-hover:scale-[1.02] focus-within:scale-[1.03] hover:shadow-lg rounded-2xl overflow-hidden">
                                <span class="inline-flex items-center px-3 rounded-l-2xl border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold text-base transition-all duration-300 group-hover:bg-gray-300 dark:group-hover:bg-gray-600">
                                    +58
                                </span>
                                <input type="text" 
                                    wire:model.live="telefono_secundario" 
                                    placeholder="4123456789" 
                                    maxlength="11"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    class="block w-full p-4 text-base text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 rounded-r-2xl transform hover:scale-[1.01]" />
                            </div>  
                        </div>

                        <!-- Email Principal -->
                        <div class="group">
                            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-envelope w-4 h-4"></i> Correo principal *
                            </label>
                            <input type="email" wire:model="email_principal" placeholder="contacto@empresa.com"
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg" />
                        </div>

                        <!-- Email Secundario -->
                        <div class="group">
                            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-envelope w-4 h-4"></i> Correo secundario
                            </label>
                            <input type="email" wire:model="email_secundario" placeholder="soporte@empresa.com"
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg" />
                        </div>

                        <!-- Actividad -->
                        <div class="group">
                            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-cog w-4 h-4"></i> Actividad *
                            </label>
                            <input type="text" wire:model="actividad" placeholder="Ej: Desarrollo de software"
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg" />
                        </div>

                        <!-- Dominio -->
                        <div class="group">
                            <label class="font-semibold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-globe w-4 h-4"></i> Dominio
                            </label>
                            <input type="text" wire:model="domain" placeholder="www.empresa.com"
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 group-hover:scale-[1.01] group-hover:shadow-lg"  />
                        </div>

                        <!-- Descripción -->
                        <div x-data="{ max: 1000, texto: @entangle('description') ?? '' }" class="relative group md:col-span-2">
                            <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-file-lines w-4 h-4"></i> Descripción de la empresa *
                            </label>
                            <textarea rows="4" x-model="texto" maxlength="1000"
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 resize-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 group-hover:scale-[1.01] group-hover:shadow-lg"
                                placeholder="Agrega una breve descripción de la empresa..."></textarea>

                            <div class="flex justify-between mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                <p class="flex items-center gap-1">
                                    <i class="fa-solid fa-info-circle w-4 h-4 text-yellow-500"></i>
                                    Máximo 1000 caracteres
                                </p>
                                <p class="font-semibold text-yellow-500 dark:text-yellow-400">
                                    <span x-text="1000 - (texto?.length || 0)"></span> restantes
                                </p>
                            </div>
                        </div>

                        <!-- Misión -->
                        <div x-data="{ max: 1000, texto: @entangle('mision') ?? '' }" class="relative group md:col-span-2">
                            <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-flag w-4 h-4"></i> Misión de la empresa *
                            </label>
                            <textarea rows="4" x-model="texto" maxlength="1000"
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 resize-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 group-hover:scale-[1.01] group-hover:shadow-lg"
                                placeholder="Agrega la misión de la empresa..."></textarea>

                            <div class="flex justify-between mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                <p class="flex items-center gap-1">
                                    <i class="fa-solid fa-info-circle w-4 h-4 text-yellow-500"></i>
                                    Máximo 1000 caracteres
                                </p>
                                <p class="font-semibold text-yellow-500 dark:text-yellow-400">
                                    <span x-text="1000 - (texto?.length || 0)"></span> restantes
                                </p>
                            </div>
                        </div>

                        <!-- Visión -->
                        <div x-data="{ max: 1000, texto: @entangle('vision') ?? '' }" class="relative group md:col-span-2">
                            <label class="flex items-center gap-2 mb-2 text-sm sm:text-base font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-eye w-4 h-4"></i> Visión de la empresa *
                            </label>
                            <textarea rows="4" x-model="texto" maxlength="1000"
                                class="block w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-300 resize-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 group-hover:scale-[1.01] group-hover:shadow-lg"
                                placeholder="Agrega la visión de la empresa..."></textarea>

                            <div class="flex justify-between mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                <p class="flex items-center gap-1">
                                    <i class="fa-solid fa-info-circle w-4 h-4 text-yellow-500"></i>
                                    Máximo 1000 caracteres
                                </p>
                                <p class="font-semibold text-yellow-500 dark:text-yellow-400">
                                    <span x-text="1000 - (texto?.length || 0)"></span> restantes
                                </p>
                            </div>
                        </div>             

                        {{-- Organigrama (PDF) --}}
                        <div class="relative group md:col-span-2">
                            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-diagram-project w-4 h-4"></i> {{ $mode === 'create' ? 'Importar organigrama (PDF)' : 'Actualizar organigrama (PDF)' }} *
                            </label>
                            <div class="flex flex-col gap-3">
                                <div class="flex-1">
                                    <input 
                                        type="file"  
                                        wire:model="organigrama"  
                                        accept=".pdf"  
                                        class="w-full p-4 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />  
                                    {{-- Mensaje de advertencia solo si NO hay PDF cargado --}}
                                    @if (!$organigrama)
                                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                            ⚠ El archivo debe ser en formato PDF obligatoriamente.
                                        </p>
                                    @endif
                                </div>
                                @if ($mode === 'edit' && isset($empresa) && $empresa->organigrama_ruta)
                                <div class="flex flex-col items-center mt-4 space-y-2">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                        <i class="fa-solid fa-diagram-project text-blue-600 dark:text-blue-400"></i>
                                        Este es el organigrama actual
                                    </p>
                                    <button type="button"
                                        onclick="window.open('{{ asset('storage/' . $empresa->organigrama_ruta) }}', '_blank')"
                                        class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-md hover:scale-105 transition-transform flex items-center justify-center gap-2 font-semibold text-sm">
                                        <i class="fa-solid fa-eye animate-pulse"></i>
                                        Ver PDF Actual
                                    </button>
                                </div>
                                @endif
                            </div>

                            {{-- Indicador de carga --}}
                            <div wire:loading wire:target="organigrama" class="flex items-center justify-center gap-3 mt-3 text-blue-600">
                                <div class="flex gap-1.5">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                                    <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                                    <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                                </div>
                                <span class="text-sm font-medium">Subiendo archivo...</span>
                            </div>

                            {{-- Confirmación de archivo cargado --}}
                            @if ($organigrama)
                                <div class="mt-4 p-5 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-800 dark:to-emerald-800 border-2 border-green-400 dark:border-green-600 rounded-2xl shadow-md animate-pulse">
                                    <div class="flex items-center gap-4">
                                        <i class="fa-solid fa-check-circle w-8 h-8 text-green-700 dark:text-green-300 animate-bounce"></i>
                                        <div class="flex-1">
                                            <p class="text-green-900 dark:text-green-100 text-base font-bold">
                                                ✓ Archivo cargado correctamente
                                            </p>
                                            <p class="text-green-800 dark:text-green-200 text-sm mt-1 truncate">
                                                📄 {{ $organigrama->getClientOriginalName() }}
                                            </p>
                                        </div>
                                        <button type="button" wire:click="$set('organigrama', null)" class="flex-shrink-0 text-green-700 dark:text-green-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                            <i class="fa-solid fa-xmark w-6 h-6"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-center gap-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        @if($mode === 'create')   
                            <x-button slate wire:click="limpiar" spinner="limpiar" label="Limpiar" icon="trash"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-gray-400 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400" />
                        @endif
                        <x-button info type="submit" spinner="save" label="{{ $mode === 'create' ? 'Guardar' : 'Guardar Cambios' }}" icon="check" 
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-xl shadow-lg hover:scale-105" />
                    </div>
                </form>
            </div>
        </div>