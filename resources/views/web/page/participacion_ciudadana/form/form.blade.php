<form id="formularioDerechoPalabra" action="{{ route('derecho-palabra.store') }}" method="POST" class="space-y-4 sm:space-y-6">
    @csrf
    
    <!-- Cédula y Nombre Completo -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Cédula*</label>
            <div class="flex">
                <span class="inline-flex items-center px-3 sm:px-4 py-2.5 sm:py-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-semibold text-sm sm:text-base">V-</span>
                <input type="text" name="cedula" placeholder="12345678" 
                    value="{{ old('cedula') }}"
                    x-data x-on:input="$el.value = $el.value.replace(/[^0-9]/g,''); if($el.value.length > 8) { $el.value = $el.value.slice(0,8) }"
                    maxlength="8"
                    class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-r-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('cedula') border-red-500 @enderror">
            </div>
            @error('cedula')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Nombre Completo*</label>
            <input type="text" name="nombre" placeholder="Juan"
                value="{{ old('nombre') }}"
                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('nombre') border-red-500 @enderror">
            @error('nombre')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Apellido y Correo -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Apellido Completo*</label>
            <input type="text" name="apellido" placeholder="Pérez"
                value="{{ old('apellido') }}"
                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('apellido') border-red-500 @enderror">
            @error('apellido')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Correo Electrónico*</label>
            <input type="email" name="email" placeholder="ejemplo@correo.com"
                value="{{ old('email') }}"
                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Teléfono Móvil y WhatsApp -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Teléfono Móvil*</label>
            <div class="flex">
                <span class="inline-flex items-center px-3 sm:px-4 py-2.5 sm:py-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-semibold text-sm sm:text-base">+58</span>
                <input type="text" name="telefono_movil" placeholder="4129765425" 
                    value="{{ old('telefono_movil') }}"
                    x-data x-on:input="$el.value = $el.value.replace(/[^0-9]/g,''); if($el.value.length > 10) { $el.value = $el.value.slice(0,10) }"
                    maxlength="11"
                    class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-r-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('telefono_movil') border-red-500 @enderror">
            </div>
            @error('telefono_movil')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">WhatsApp*</label>
            <div class="flex">
                <span class="inline-flex items-center px-3 sm:px-4 py-2.5 sm:py-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-semibold text-sm sm:text-base">+58</span>
                <input type="text" name="whatsapp" placeholder="4129765425" 
                    value="{{ old('whatsapp') }}"
                    x-data x-on:input="$el.value = $el.value.replace(/[^0-9]/g,''); if($el.value.length > 10) { $el.value = $el.value.slice(0,10) }"
                    maxlength="11"
                    class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-r-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('whatsapp') border-red-500 @enderror">
            </div>
            @error('whatsapp')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Sesión Municipal -->
    <div>
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Sesión Municipal*</label>
        <select name="sesion_municipal_id" id="sesion_municipal_id"
            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('sesion_municipal_id') border-red-500 @enderror">
            <option value="">Seleccione una sesión</option>
            @forelse($sesionesProximas as $sesion)
                <option value="{{ $sesion['id'] }}" {{ old('sesion_municipal_id') == $sesion['id'] ? 'selected' : '' }}>
                    {{ $sesion['titulo'] }} - {{ $sesion['fecha_hora'] }}
                </option>
            @empty
                <option value="" disabled>No hay sesiones disponibles</option>
            @endforelse
        </select>
        @error('sesion_municipal_id')
            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
<!-- Comisión -->
<div>
    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Comisiónes disponibles</label>
    <select name="comision_id" id="comision_id"
        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('comision_id') border-red-500 @enderror">
        <option value="">Seleccione una comisión</option>
        @forelse($comisiones as $comision)
            <option value="{{ $comision->id }}" {{ old('comision_id') == $comision->id ? 'selected' : '' }}>
                {{ $comision->nombre }}
            </option>
        @empty
            <option value="" disabled>No hay comisiones disponibles</option>
        @endforelse
    </select>
    @error('comision_id')
        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
    <!-- Motivo de Solicitud -->
    <div>
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Motivo de Solicitud del Derecho de Palabra*</label>
        <textarea name="motivo_solicitud" rows="5" placeholder="Describa el motivo de su solicitud para participar en la sesión..."
            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('motivo_solicitud') border-red-500 @enderror">{{ old('motivo_solicitud') }}</textarea>
        @error('motivo_solicitud')
            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Términos y condiciones -->
    <div class="flex items-start sm:items-center gap-2 sm:gap-3">
        <input type="checkbox" name="acepta_terminos" id="terms" value="1"
            {{ old('acepta_terminos') ? 'checked' : '' }}
            class="w-5 h-5 sm:w-6 sm:h-6 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer mt-0.5 sm:mt-0 @error('acepta_terminos') border-red-500 @enderror">
        <label for="terms" class="text-xs sm:text-base text-gray-700 cursor-pointer">
            Acepto los términos y condiciones
        </label>
        @error('acepta_terminos')
            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Botón Enviar -->
    <button type="submit" id="btnEnviar" class="btn-primary w-full py-3 sm:py-4 rounded-lg sm:rounded-xl font-semibold text-sm sm:text-lg flex items-center justify-center gap-2 hover:opacity-90 transition-all duration-300 transform hover:scale-105 hover:shadow-lg group">
        <i class="fas fa-paper-plane text-white animate-bounce"></i>     
        <span class="text-white">Enviar Solicitud</span>
    </button>
</form>

<!-- Botón animation -->
<style>
    @keyframes floatIcon {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-8px) rotate(5deg);
        }
    }

    #btnEnviar i:first-child {
        animation: floatIcon 2s ease-in-out infinite;
    }
</style>

@include('web.page.participacion_ciudadana.js.sweetalert')