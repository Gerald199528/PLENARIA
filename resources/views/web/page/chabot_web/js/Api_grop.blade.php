<script>
function hexToRgb(hex) {
    hex = hex.replace('#', '');
    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);
    return r + ', ' + g + ', ' + b;
}
</script>

<script>
function hexToRgb(hex) {
    hex = hex.replace('#', '');
    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);
    return r + ', ' + g + ', ' + b;
}
</script>

<script>
const chatbotToggle = document.getElementById('chatbot-toggle');
const chatbotClose = document.getElementById('chatbot-close');
const chatbotWindow = document.getElementById('chatbot-window');
const messagesContainer = document.getElementById('messages-container');
const chatbotInput = document.getElementById('chatbot-input');
const chatbotSend = document.getElementById('chatbot-send');

let messageId = 1;
let isLoading = false;
let isFirstOpen = true;
let notificationInterval = null;
let notificationTimeout = null;

function getCsrfToken() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute('content') : '';
}

const notificationMessages = [
    { emoji: '👋', text: '¡Hola soy "PLENARIA" tu asistente virtual! ¿Cómo estás?', duration: 6000 },
    { emoji: '💬', text: '¿Puedo ayudarte?', duration: 6000 },
    { emoji: '✨', text: 'Cuéntame qué necesitas', duration: 6000 },
    { emoji: '🤖', text: 'Te ayudaré si deseas', duration: 6000 },
    { emoji: '📱', text: '¿Tienes preguntas?', duration: 6000 }
];

let messageIndex = 0;

function showNotification() {
    const notificationMessage = document.getElementById('chatbot-notification');
    const currentMessage = notificationMessages[messageIndex];
    
    notificationMessage.innerHTML = `
        <div class="flex items-center gap-2">
            <span class="text-lg">${currentMessage.emoji}</span>
            <span>${currentMessage.text}</span>
        </div>
        <div class="absolute -bottom-3 right-8 w-0 h-0 border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-white"></div>
    `;
    
    notificationMessage.style.display = 'block';
    notificationMessage.style.opacity = '1';
    notificationMessage.style.transition = 'opacity 0.3s ease';
    const duration = currentMessage.duration || 6000;
    const gapBetweenMessages = 30000;
    messageIndex = (messageIndex + 1) % notificationMessages.length; 
    notificationTimeout = setTimeout(() => {
        notificationMessage.style.opacity = '0'; 
        notificationTimeout = setTimeout(showNotification, gapBetweenMessages);
    }, duration);
}

function startNotifications() { 
    stopNotifications();
    notificationTimeout = setTimeout(showNotification, 8000);
}

function stopNotifications() {
    if (notificationInterval) {
        clearInterval(notificationInterval);
        notificationInterval = null;
    }
    if (notificationTimeout) {
        clearTimeout(notificationTimeout);
        notificationTimeout = null;
    }
    const notification = document.getElementById('chatbot-notification');
    if (notification) {
        notification.style.display = 'none';
        notification.style.opacity = '0';
    }
}

function addMessage(text, sender) {
    messageId++;
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${sender === 'user' ? 'justify-end' : 'justify-start'} animate-fade-in`;

    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    messageDiv.innerHTML = `
        <div class="max-w-xs sm:max-w-sm px-3 sm:px-4 py-2 rounded-2xl ${
            sender === 'user'
                ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-br-none'
                : 'bg-gray-200 text-gray-800 rounded-bl-none'
        } shadow-sm">
            <p class="text-xs sm:text-sm">${escapeHtml(text)}</p>
            <span class="text-xs opacity-70 mt-1 block">${time}</span>
        </div>
    `;
    messagesContainer.appendChild(messageDiv);
    scrollToBottom();
}

function addQuickOptions() {
    // Obtener el color primario del CSS custom property
    const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--chatbot-primary').trim();
    
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'flex flex-col gap-2 animate-fade-in p-2';
    optionsDiv.innerHTML = `
        <p class="text-xs text-gray-500 px-2 mb-2 font-medium">¿Qué te gustaría explorar?</p>
        
        <button class="quick-option-btn group text-left px-4 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md" 
                style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%); border: 1px solid rgba(59, 130, 246, 0.3);"
                onmouseover="this.style.background='linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(99, 102, 241, 0.2) 100%)'; this.style.borderColor='rgba(59, 130, 246, 0.5)'"
                onmouseout="this.style.background='linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%)'; this.style.borderColor='rgba(59, 130, 246, 0.3)'"
                data-link="#nosotros" 
                data-message="📖 Te estoy llevando a Nosotros, donde podrás conocer más sobre nuestra organización.">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors" 
                      style="background-color: rgba(59, 130, 246, 0.15);">
                    <i class="fas fa-info-circle" style="color: ${primaryColor}"></i>
                </span>
                <span class="font-medium" style="color: ${primaryColor}">Nosotros</span>
            </div>
        </button>

        <button class="quick-option-btn group text-left px-4 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md" 
                style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%); border: 1px solid rgba(168, 85, 247, 0.3);"
                onmouseover="this.style.background='linear-gradient(135deg, rgba(168, 85, 247, 0.2) 0%, rgba(236, 72, 153, 0.2) 100%)'; this.style.borderColor='rgba(168, 85, 247, 0.5)'"
                onmouseout="this.style.background='linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%)'; this.style.borderColor='rgba(168, 85, 247, 0.3)'"
                data-link="#noticias" 
                data-message="📰 Te dirijo a la sección de Noticias para que te mantengas informado.">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors" 
                      style="background-color: rgba(168, 85, 247, 0.15);">
                    <i class="fas fa-newspaper text-purple-600"></i>
                </span>
                <span class="font-medium text-purple-700">Noticias</span>
            </div>
        </button>

        <button class="quick-option-btn group text-left px-4 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md" 
                style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%); border: 1px solid rgba(16, 185, 129, 0.3);"
                onmouseover="this.style.background='linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%)'; this.style.borderColor='rgba(16, 185, 129, 0.5)'"
                onmouseout="this.style.background='linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%)'; this.style.borderColor='rgba(16, 185, 129, 0.3)'"
                data-link="#localidad" 
                data-message="📍 Te llevo a Localidad para que conozcas información sobre nuestra zona.">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors" 
                      style="background-color: rgba(16, 185, 129, 0.15);">
                    <i class="fas fa-map-marker-alt text-green-600"></i>
                </span>
                <span class="font-medium text-green-700">Localidad</span>
            </div>
        </button>

        <button class="quick-option-btn group text-left px-4 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md" 
                style="background: linear-gradient(135deg, rgba(251, 146, 60, 0.1) 0%, rgba(245, 158, 11, 0.1) 100%); border: 1px solid rgba(251, 146, 60, 0.3);"
                onmouseover="this.style.background='linear-gradient(135deg, rgba(251, 146, 60, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%)'; this.style.borderColor='rgba(251, 146, 60, 0.5)'"
                onmouseout="this.style.background='linear-gradient(135deg, rgba(251, 146, 60, 0.1) 0%, rgba(245, 158, 11, 0.1) 100%)'; this.style.borderColor='rgba(251, 146, 60, 0.3)'"
                data-link="#participacion" 
                data-message="👥 Te dirijo a Participación donde puedes involucrarte con la comunidad.">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors" 
                      style="background-color: rgba(251, 146, 60, 0.15);">
                    <i class="fas fa-users text-orange-600"></i>
                </span>
                <span class="font-medium text-orange-700">Participación</span>
            </div>
        </button>

        <button class="quick-option-btn group text-left px-4 py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md" 
                style="background: linear-gradient(135deg, rgba(100, 116, 139, 0.1) 0%, rgba(71, 85, 105, 0.1) 100%); border: 1px solid rgba(100, 116, 139, 0.3);"
                onmouseover="this.style.background='linear-gradient(135deg, rgba(100, 116, 139, 0.2) 0%, rgba(71, 85, 105, 0.2) 100%)'; this.style.borderColor='rgba(100, 116, 139, 0.5)'"
                onmouseout="this.style.background='linear-gradient(135deg, rgba(100, 116, 139, 0.1) 0%, rgba(71, 85, 105, 0.1) 100%)'; this.style.borderColor='rgba(100, 116, 139, 0.3)'"
                data-link="documentos" 
                data-message="📄 Te envío a Documentos Legales para acceder a nuestra información oficial.">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors" 
                      style="background-color: rgba(100, 116, 139, 0.15);">
                    <i class="fas fa-file-alt text-slate-600"></i>
                </span>
                <span class="font-medium text-slate-700">Documentos Legales</span>
            </div>
        </button>
    `;
    
    messagesContainer.appendChild(optionsDiv);
    scrollToBottom();
    
    // Event listeners para los botones
    document.querySelectorAll('.quick-option-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const link = this.getAttribute('data-link');
            const mensaje = this.getAttribute('data-message');
            
            addMessage(mensaje, 'bot');
            
            setTimeout(() => {
                chatbotWindow.classList.add('hidden');
                chatbotToggle.classList.remove('hidden');
                startNotifications();
                
                if (link === 'documentos') {
                    window.location.href = '/instrumentos_legales';
                } else {
                    window.location.href = '/' + link;
                }
            }, 1500);
        });
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showTypingIndicator() {
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typing-indicator';
    typingDiv.className = 'flex justify-start animate-fade-in';
    typingDiv.innerHTML = `
        <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-2xl rounded-bl-none">
            <div class="flex space-x-2 bounce-dots">
                <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
            </div>
        </div>
    `;
    messagesContainer.appendChild(typingDiv);
    scrollToBottom();
}

function removeTypingIndicator() {
    const typing = document.getElementById('typing-indicator');
    if (typing) typing.remove();
}

function scrollToBottom() {
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

async function sendMessage() {
    const text = chatbotInput.value.trim();
    if (!text || isLoading) return;

    addMessage(text, 'user');
    chatbotInput.value = '';
    isLoading = true;
    showTypingIndicator();
    try {
        const response = await fetch('/chatbot/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ message: text })
        });

        removeTypingIndicator();

        if (!response.ok) {          
            const errorData = await response.json();
            throw new Error(errorData.message || `Error HTTP: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success) {            
            addMessage(data.message, 'bot');
        } else {          
            addMessage('El asistente no pudo generar una respuesta: ' + (data.message || 'Error desconocido'), 'bot');
        }

    } catch (error) {
        console.error('Error al enviar mensaje:', error);
        removeTypingIndicator();      
        addMessage('⚠️ Lo siento, no pude conectarme con el asistente. Por favor, revisa tu conexión e inténtalo de nuevo. Error: ' + error.message, 'bot');
    } finally {
        isLoading = false;
    }
}

chatbotToggle.addEventListener('click', () => {
    const isWindowHidden = chatbotWindow.classList.contains('hidden');
    
    if (isWindowHidden) {
        chatbotWindow.classList.remove('hidden');
        chatbotToggle.classList.add('hidden');
        stopNotifications();
        
        if (isFirstOpen && messagesContainer.children.length === 0) {
            addMessage('¡Hola! 👋 Soy PLENARIA tu Asistente virtual. ¿En qué puedo ayudarte hoy?', 'bot');
            setTimeout(() => {
                addQuickOptions();
            }, 500);
            isFirstOpen = false;
        }
        
        chatbotInput.focus();
    } else {                 
        chatbotWindow.classList.add('hidden');
        chatbotToggle.classList.remove('hidden');
        startNotifications(); 
    }
});

chatbotClose.addEventListener('click', () => {
    chatbotWindow.classList.add('hidden');
    chatbotToggle.classList.remove('hidden');
    startNotifications(); 
});

chatbotSend.addEventListener('click', sendMessage);

chatbotInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter' && !isLoading) {
        e.preventDefault();
        sendMessage();
    }
});
window.addEventListener('load', () => {
    startNotifications();
});
</script>