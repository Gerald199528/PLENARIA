{{-- web/page/chabot_web/index.blade.php --}}
@php
$faviconPath = \App\Models\Setting::get('logo_icon'); 
$faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('default-favicon.ico');

$logoPath = \App\Models\Setting::get('logo_horizontal_background_solid');
$logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
@endphp

@include('web.page.chabot_web.style.chatbot')
<div id="chatbot-container" class="fixed bottom-24 sm:bottom-28 right-4 sm:right-5 z-50" style="position: fixed;">
    
    <!-- Notificación de mensaje -->
<div id="chatbot-notification" class="absolute -top-24 right-0 bg-white text-gray-800 px-6 py-4 rounded-2xl 
shadow-2xl text-base font-semibold border-l-4 border-blue-600 z-50" style="display: none; width: 280px; animation: bounce-slow 3s infinite;">
     
        <div class="absolute -bottom-3 right-8 w-0 h-0 border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-white"></div>
    </div>
    
    <!-- Botón Flotante -->
    <button id="chatbot-toggle" class="gradient-animated text-white rounded-full p-0 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center animate-bounce-chatbot overflow-hidden">
        @if($logoUrl)
            <img id="chatbot-button-logo" src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-cover">
        @else
            <i class="fas fa-comments text-lg sm:text-2xl"></i>
        @endif
    </button>

    <!-- Ventana del Chat -->
<div id="chatbot-window" class="hidden bg-white rounded-2xl shadow-2xl flex flex-col w-[95vw] sm:w-96 h-[90vh] sm:h-[600px] max-h-screen overflow-hidden absolute bottom-16 sm:bottom-20 right-1/2 sm:right-0 translate-x-1/2 sm:translate-x-0">
        <!-- Header con gradiente animado -->
        <div class="gradient-animated text-white p-3 sm:p-4 flex items-center justify-between rounded-t-2xl">
            <div class="flex items-center gap-2">
                @if($logoUrl)
                    <div class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full shadow-md overflow-hidden bg-white/20">
                        <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-cover">
                    </div>
                @endif
                <div>
                    <h3 class="font-bold text-base sm:text-lg">PLENARIA AI</h3>
                    <p class="text-xs text-blue-100">Asistente virtual</p>
                </div>
            </div>
            <button id="chatbot-close" class="hover:bg-white/20 p-2 rounded-full transition-all">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>

        <!-- Área de Mensajes -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-3 sm:p-4 bg-gradient-to-b from-gray-50 to-white space-y-2 sm:space-y-3">
            <!-- Los mensajes se añadirán aquí -->
        </div>

        <!-- Input -->
        <div class="border-t border-gray-200 p-3 sm:p-4 bg-white rounded-b-2xl">
            <div class="flex gap-2">
                <input 
                    id="chatbot-input" 
                    type="text" 
                    placeholder="Pregunta..." 
                    class="flex-1 border border-gray-300 rounded-full px-3 sm:px-4 py-2 text-sm sm:text-base focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all"
                />
                <button id="chatbot-send" class="gradient-animated text-white rounded-full p-2 transition-all w-10 h-10 sm:w-10 sm:h-10 flex items-center justify-center flex-shrink-0 hover:shadow-lg">
                    <i class="fas fa-paper-plane text-sm sm:text-base"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@include('web.page.chabot_web.js.Api_grop')