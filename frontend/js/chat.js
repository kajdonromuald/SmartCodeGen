// C:\xampp\htdocs\SmartCodeGen\js\chat.js

document.addEventListener('DOMContentLoaded', () => {
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');
    const chatBox = document.getElementById('chat-box');
    const sendButton = chatForm.querySelector('button[type="submit"]');

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    
    // Módosított displayMessage függvény a CSS osztályok kezelésére
   function displayMessage(message, type) {
    const messageDiv = document.createElement('div');
    
    const classes = type.split(' ');
    messageDiv.classList.add('chat-message', ...classes);
    
    messageDiv.innerHTML = message;
    chatBox.appendChild(messageDiv);
    scrollToBottom();

    // *** MÓDOSÍTOTT KÓD: Célzottan színezünk, és csak a kódblokkokat ***
    const codeBlocks = messageDiv.querySelectorAll('pre code');
    codeBlocks.forEach(codeBlock => {
        try {
            Prism.highlightElement(codeBlock);
        } catch (e) {
            console.error("Hiba történt a Prism.js futtatása közben:", e);
        }
    });
}

    userInput.addEventListener('input', () => {
        userInput.style.height = 'auto';
        userInput.style.height = userInput.scrollHeight + 'px';
    });

    userInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    chatForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        userInput.disabled = true;
        sendButton.disabled = true;

        const userMessage = userInput.value.trim();

        if (userMessage === '') {
            userInput.disabled = false;
            sendButton.disabled = false;
            return;
        }

        displayMessage(userMessage, 'user-message');

        const originalMessage = userInput.value;
        userInput.value = '';
        userInput.style.height = 'auto';
        
        const aiTypingDiv = document.createElement('div');
        aiTypingDiv.classList.add('chat-message', 'ai-text-message', 'typing-indicator');
        aiTypingDiv.innerHTML = '<div class="spinner"></div> Dolgozom a válaszon...';
        chatBox.appendChild(aiTypingDiv);
        scrollToBottom();

        try {
            const response = await fetch('index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `user_message=${encodeURIComponent(originalMessage)}`
            });
            
            if (!response.ok) {
                throw new Error(`HTTP hiba! Státusz: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.status === 'success') {
                displayMessage(data.message, 'ai-text-message');
            } else {
                // Javítás: Itt is külön adjuk át az osztályokat
                displayMessage('Hiba történt: ' + data.message, 'ai-text-message error-message');
                userInput.value = originalMessage;
            }

        } catch (error) {
            console.error('Fetch error:', error);
            // Javítás: Itt is külön adjuk át az osztályokat
            displayMessage('Hálózati hiba történt. Kérjük, ellenőrizze az internetkapcsolatot, vagy próbálja újra később.', 'ai-text-message error-message');
            userInput.value = originalMessage;
        } finally {
            if (aiTypingDiv.parentNode === chatBox) {
                chatBox.removeChild(aiTypingDiv);
            }
            userInput.disabled = false;
            sendButton.disabled = false;
            userInput.focus();
        }
    });

    scrollToBottom();
});