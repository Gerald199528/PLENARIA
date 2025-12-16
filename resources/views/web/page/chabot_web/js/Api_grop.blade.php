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
    { emoji: '👋', text: '¡Hola soy Plenaria! ¿Cómo estás?', duration: 4000 },
    { emoji: '💬', text: '¿Puedo ayudarte?', duration: 4000 },
    { emoji: '✨', text: 'Cuéntame qué necesitas', duration: 4000 },
    { emoji: '🤖', text: 'Te ayudaré si deseas', duration: 4000 },
    { emoji: '📱', text: '¿Tienes preguntas?', duration: 4000 }
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
    const duration = currentMessage.duration || 4000;
    const gapBetweenMessages = 18000;
    messageIndex = (messageIndex + 1) % notificationMessages.length; 
    notificationTimeout = setTimeout(() => {
        notificationMessage.style.opacity = '0'; 
        notificationTimeout = setTimeout(showNotification, gapBetweenMessages);
    }, duration);
}

function startNotifications() { 
    stopNotifications();
    notificationTimeout = setTimeout(showNotification, 2000);
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
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'flex flex-col gap-2 animate-fade-in p-2';
    optionsDiv.innerHTML = `
        <p class="text-xs text-gray-600 px-2 font-semibold">Selecciona una sección</p>
        <button class="quick-option-btn text-left px-3 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs sm:text-sm transition-all border border-blue-200" data-link="#nosotros" data-message="📖 Te estoy llevando a Nosotros, donde podrás conocer más sobre nuestra organización.">
            <i class="fas fa-info-circle mr-2"></i> Nosotros
        </button>
        <button class="quick-option-btn text-left px-3 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs sm:text-sm transition-all border border-blue-200" data-link="#noticias" data-message="📰 Te dirijo a la sección de Noticias para que te mantengas informado.">
            <i class="fas fa-newspaper mr-2"></i> Noticias
        </button>
        <button class="quick-option-btn text-left px-3 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs sm:text-sm transition-all border border-blue-200" data-link="#localidad" data-message="📍 Te llevo a Localidad para que conozcas información sobre nuestra zona.">
            <i class="fas fa-map-marker-alt mr-2"></i> Localidad
        </button>
        <button class="quick-option-btn text-left px-3 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs sm:text-sm transition-all border border-blue-200" data-link="#participacion" data-message="👥 Te dirijo a Participación donde puedes involucrarte con la comunidad.">
            <i class="fas fa-users mr-2"></i> Participación
        </button>
        <button class="quick-option-btn text-left px-3 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs sm:text-sm transition-all border border-blue-200" data-link="documentos" data-message="📄 Te envío a Documentos Legales para acceder a nuestra información oficial.">
            <i class="fas fa-file-alt mr-2"></i> Documentos Legales
        </button>
    `;
    messagesContainer.appendChild(optionsDiv);
    scrollToBottom();
    
    document.querySelectorAll('.quick-option-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const link = this.getAttribute('data-link');
            const mensaje = this.getAttribute('data-message');
            
            addMessage(mensaje, 'bot');
            
            setTimeout(() => {
                if (link === 'documentos') {
                    window.location.href = '/instrumentos_legales';
                } else {
                    window.location.href = '/' + link;
                }
            }, 1000);
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