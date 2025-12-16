{{-- web/page/chabot_web/index.blade.php --}}
@php
$botGifUrl = asset('bot.gif');
$primaryColor = \App\Models\Setting::get('primary_color', '#1d4ed8');
$secondaryColor = \App\Models\Setting::get('secondary_color', '#3b82f6');
$buttonColor = \App\Models\Setting::get('button_color', '#1d4ed8');
@endphp

@include('web.page.chabot_web.style.chatbot')

<div id="chatbot-container" class="fixed bottom-24 sm:bottom-28 right-4 sm:right-5 z-50" style="position: fixed;">
    
    <!-- Notificación de mensaje -->
    <div id="chatbot-notification" class="absolute -top-24 right-0 bg-white text-gray-800 px-4 sm:px-6 py-3 sm:py-4 rounded-2xl shadow-2xl text-sm sm:text-base font-semibold z-50" 
         style="display: none; width: 260px; animation: bounce-slow 3s infinite; border-left: 4px solid var(--chatbot-primary);">
        <div class="absolute -bottom-3 right-6 sm:right-8 w-0 h-0 border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-white"></div>
    </div>

    <!-- Botón Flotante -->
    <button id="chatbot-toggle" class="chatbot-gradient-animated text-white rounded-full p-0 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-110 w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center animate-bounce-chatbot overflow-hidden" style="box-shadow: 0 0 20px rgba(59, 130, 246, 0.5), 0 0 40px rgba(59, 130, 246, 0.3);">
        <img id="chatbot-button-bot" src="{{ $botGifUrl }}" alt="Bot Assistant" class="w-full h-full object-cover rounded-full">
    </button>

    <!-- Ventana del Chat -->
    <div id="chatbot-window" class="hidden bg-white rounded-xl sm:rounded-2xl shadow-2xl flex flex-col fixed bottom-20 right-4 sm:bottom-24 sm:right-6 h-[70vh] sm:h-[600px] w-[calc(100vw-2rem)] sm:w-96 max-h-[85vh] overflow-hidden">
        
        <!-- Header con gradiente animado -->
        <div class="chatbot-gradient-animated text-white p-2 sm:p-4 flex items-center justify-between rounded-t-xl sm:rounded-t-2xl">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded-full shadow-md overflow-hidden bg-white/20 flex-shrink-0">
                    <img src="{{ $botGifUrl }}" alt="Bot" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <h3 class="font-bold text-sm sm:text-lg truncate">PLENARIA AI</h3>
                    <p class="text-xs opacity-90">Asistente virtual</p>
                </div>
            </div>
            <button id="chatbot-close" class="hover:bg-white/20 p-2 rounded-full transition-all flex-shrink-0">
                <i class="fas fa-times text-base sm:text-xl"></i>
            </button>
        </div>

        <!-- Área de Mensajes -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-2 sm:p-4 bg-gradient-to-b from-gray-50 to-white space-y-2 sm:space-y-3">
            <!-- Los mensajes se añadirán aquí -->
        </div>

        <!-- Input -->
        <div class="border-t border-gray-200 p-2 sm:p-4 bg-white rounded-b-xl sm:rounded-b-2xl flex-shrink-0">
            <div class="flex gap-1 sm:gap-2">
                <input 
                    id="chatbot-input" 
                    type="text" 
                    placeholder="Pregunta..." 
                    class="flex-1 border border-gray-300 rounded-full px-2 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-base focus:outline-none transition-all"
                    style="border-color: var(--chatbot-primary); focus:ring-color: var(--chatbot-primary);"
                    onfocus="this.style.borderColor='var(--chatbot-primary)'; this.style.boxShadow='0 0 0 3px rgba(' + hexToRgb(getComputedStyle(document.documentElement).getPropertyValue('--chatbot-primary').trim()) + ', 0.1)'"
                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                />
                <button id="chatbot-send" class="chatbot-gradient-animated text-white rounded-full p-2 transition-all w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center flex-shrink-0 hover:shadow-lg">
                    <i class="fas fa-paper-plane text-xs sm:text-base"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@include('web.page.chabot_web.js.Api_grop')