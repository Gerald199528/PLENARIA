{{-- web/page/chabot_web/index.blade.php --}}
@php
$botGifUrl = asset('bot.gif');
$primaryColor = \App\Models\Setting::get('primary_color', '#1d4ed8');
$secondaryColor = \App\Models\Setting::get('secondary_color', '#3b82f6');
$buttonColor = \App\Models\Setting::get('button_color', '#1d4ed8');
@endphp

@include('web.page.chabot_web.style.chatbot')

<div id="chatbot-container" class="fixed bottom-16 sm:bottom-20 md:bottom-24 lg:bottom-28 right-3 sm:right-4 md:right-5 z-50" style="position: fixed;">

    <!-- Notificación de mensaje -->
      <div id="chatbot-notification" class="absolute -top-24 right-0 bg-white text-gray-800 px-4 sm:px-6 py-3 sm:py-4 rounded-2xl shadow-2xl text-sm sm:text-base font-semibold z-50"
         style="display: none; width: 260px; animation: bounce-slow 3s infinite; border-left: 4px solid var(--chatbot-primary);">
        <div class="absolute -bottom-3 right-6 sm:right-8 w-0 h-0 border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-white"></div>
    </div>

    <!-- Botón Flotante -->
    <button id="chatbot-toggle" class="chatbot-gradient-animated text-white rounded-full p-0 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-110 w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 lg:w-20 lg:h-20 flex items-center justify-center animate-bounce-chatbot overflow-hidden" style="box-shadow: 0 0 20px rgba(59, 130, 246, 0.5), 0 0 40px rgba(59, 130, 246, 0.3);">
        <img id="chatbot-button-bot" src="{{ $botGifUrl }}" alt="Bot Assistant" class="w-full h-full object-cover rounded-full">
    </button>

    <!-- Ventana del Chat -->
    <div id="chatbot-window" class="hidden bg-white rounded-lg sm:rounded-xl md:rounded-2xl shadow-2xl flex flex-col fixed bottom-14 sm:bottom-16 md:bottom-20 lg:bottom-24 right-3 sm:right-4 md:right-5 lg:right-6 h-[60vh] sm:h-[500px] md:h-[600px] w-[calc(100vw-1.5rem)] sm:w-80 md:w-96 max-h-[80vh] overflow-hidden">

        <!-- Header con gradiente animado -->
        <div class="chatbot-gradient-animated text-white p-2 sm:p-3 md:p-4 flex items-center justify-between rounded-t-lg sm:rounded-t-xl md:rounded-t-2xl">
            <div class="flex items-center gap-1.5 sm:gap-2 min-w-0">
                <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full shadow-md overflow-hidden bg-white/20 flex-shrink-0">
                    <img src="{{ $botGifUrl }}" alt="Bot" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <h3 class="font-bold text-xs sm:text-sm md:text-lg truncate">PLENARIA AI</h3>
                    <p class="text-xs opacity-90">Asistente virtual</p>
                </div>
            </div>
            <button id="chatbot-close" class="hover:bg-white/20 p-1.5 sm:p-2 rounded-full transition-all flex-shrink-0">
                <i class="fas fa-times text-xs sm:text-base md:text-xl"></i>
            </button>
        </div>

        <!-- Área de Mensajes -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-2 sm:p-3 md:p-4 bg-gradient-to-b from-gray-50 to-white space-y-1.5 sm:space-y-2 md:space-y-3">
            <!-- Los mensajes se añadirán aquí -->
        </div>

        <!-- Input -->
        <div class="border-t border-gray-200 p-2 sm:p-3 md:p-4 bg-white rounded-b-lg sm:rounded-b-xl md:rounded-b-2xl flex-shrink-0">
            <div class="flex gap-1 sm:gap-1.5 md:gap-2">
                <input
                    id="chatbot-input"
                    type="text"
                    placeholder="Pregunta..."
                    class="flex-1 border border-gray-300 rounded-full px-2 sm:px-3 md:px-4 py-1 sm:py-1.5 md:py-2 text-xs sm:text-sm md:text-base focus:outline-none transition-all"
                    style="border-color: var(--chatbot-primary); focus:ring-color: var(--chatbot-primary);"
                    onfocus="this.style.borderColor='var(--chatbot-primary)'; this.style.boxShadow='0 0 0 3px rgba(' + hexToRgb(getComputedStyle(document.documentElement).getPropertyValue('--chatbot-primary').trim()) + ', 0.1)'"
                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                />
                <button id="chatbot-send" class="chatbot-gradient-animated text-white rounded-full p-1.5 sm:p-2 transition-all w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 flex items-center justify-center flex-shrink-0 hover:shadow-lg">
                    <i class="fas fa-paper-plane text-xs sm:text-xs md:text-base"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@include('web.page.chabot_web.js.Api_grop')
