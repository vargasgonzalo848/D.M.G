<link rel="stylesheet" href="/odontologia/css/chatbot.css">
<button id="chatbot-btn" onclick="toggleChatbot()">💬</button>

<div id="chatbot-window">
    <div id="chatbot-header">
        <span>🦷 Asistente Virtual</span>
        <button id="chatbot-close" onclick="toggleChatbot()">×</button>
    </div>
    <div id="chatbot-messages">
        <div class="chat-msg bot">
            ¡Hola! 👋 Soy tu asistente virtual de Odontología Fanny. ¿En qué puedo ayudarte hoy?
        </div>
    </div>
    <div id="chatbot-input-area">
        <input type="text" id="chatbot-input" placeholder="Escribe tu mensaje..." onkeypress="if(event.key==='Enter') enviarMensajeChatbot()">
        <button id="chatbot-send" onclick="enviarMensajeChatbot()">Enviar</button>
    </div>
</div>

<script>
function toggleChatbot() {
    const window = document.getElementById('chatbot-window');
    window.classList.toggle('active');
    
    if(window.classList.contains('active')) {
        document.getElementById('chatbot-input').focus();
    }
}

async function enviarMensajeChatbot() {
    const input = document.getElementById('chatbot-input');
    const mensaje = input.value.trim();
    
    if(!mensaje) return;
    
    const messagesDiv = document.getElementById('chatbot-messages');
    messagesDiv.innerHTML += `<div class="chat-msg user">${mensaje}</div>`;
    input.value = '';
    
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    
    const typingIndicator = document.createElement('div');
    typingIndicator.className = 'typing-indicator';
    typingIndicator.innerHTML = '<span></span><span></span><span></span>';
    messagesDiv.appendChild(typingIndicator);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    
    const sendBtn = document.getElementById('chatbot-send');
    sendBtn.disabled = true;
    
    try {
        const respuesta = await fetch("http://localhost:5000", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ mensaje: mensaje })
        });
        
        typingIndicator.remove();
        
        if(!respuesta.ok) {
            throw new Error('Error en la conexión');
        }
        
        const data = await respuesta.json();
        
        messagesDiv.innerHTML += `<div class="chat-msg bot">${data.respuesta}</div>`;
        
    } catch(error) {
        typingIndicator.remove();
        
        messagesDiv.innerHTML += `<div class="chat-msg bot">
            ⚠️ Lo siento, no pude conectarme con el servidor. Por favor, verifica que el servidor esté corriendo en <code>localhost:5000</code>
        </div>`;
    }
    
    sendBtn.disabled = false;
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}
</script>