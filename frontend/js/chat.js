// C:\xampp\htdocs\SmartCodeGen\js\chat.js

function sendMessage(event) {
    event.preventDefault();

    const input = document.getElementById('user-input');
    const message = input.value.trim();

    if (message === '') return;

    const chatBox = document.getElementById('chat-box');

    const userMessage = document.createElement('div');
    userMessage.className = 'chat-message user-message';
    userMessage.textContent = message;
    chatBox.appendChild(userMessage);

    const botMessage = document.createElement('div');
    botMessage.className = 'chat-message ai-text-message';
    botMessage.innerHTML = '<span class="typing-indicator">🤖 Dolgozom a válaszon...</span>';
    chatBox.appendChild(botMessage);

    chatBox.scrollTop = chatBox.scrollHeight;
    input.value = '';

    const formData = new FormData();
    formData.append('user_message', message);
    
    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Hálózati hiba: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        // Frissítsük az utolsó AI üzenetet a tényleges tartalommal
        botMessage.innerHTML = data.message;
        
        // *** ÚJ KÓD: Visszajelzés gombok hozzáadása, ha sikeres a válasz
        if (data.status === 'success' && data.message_id) {
            const feedbackContainer = document.createElement('div');
            feedbackContainer.className = 'feedback-container';
            feedbackContainer.innerHTML = `
                <button class="feedback-button" data-message-id="${data.message_id}" data-feedback-type="like">
                    <i class="far fa-thumbs-up"></i>
                </button>
                <button class="feedback-button" data-message-id="${data.message_id}" data-feedback-type="dislike">
                    <i class="far fa-thumbs-down"></i>
                </button>
            `;
            botMessage.appendChild(feedbackContainer);
            
            // Eseménykezelők hozzáadása a gombokhoz
            feedbackContainer.querySelectorAll('.feedback-button').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    const button = e.currentTarget;
                    const messageId = button.dataset.messageId;
                    const feedbackType = button.dataset.feedbackType;
                    
                    const feedbackFormData = new FormData();
                    feedbackFormData.append('action', 'save_feedback');
                    feedbackFormData.append('message_id', messageId);
                    feedbackFormData.append('feedback_type', feedbackType);
                    
                    // Küldjük el a visszajelzést a backendnek
                    fetch('index.php', {
                        method: 'POST',
                        body: feedbackFormData
                    })
                    .then(response => response.json())
                    .then(feedbackData => {
                        if (feedbackData.status === 'success') {
                            // A gombok inaktívvá tétele, és a szín megváltoztatása
                            feedbackContainer.querySelectorAll('.feedback-button').forEach(btn => {
                                btn.disabled = true;
                                if (btn.dataset.feedbackType === feedbackType) {
                                    btn.classList.add('active');
                                }
                            });
                        } else {
                            console.error('Hiba a visszajelzés mentésekor:', feedbackData.message);
                        }
                    })
                    .catch(err => {
                        console.error('Hiba a visszajelzés küldésekor:', err);
                    });
                });
            });
        }

        chatBox.scrollTop = chatBox.scrollHeight;
        
        // Kódblokkok felismerése és a másoló gomb funkcionalitásának hozzáadása
        const codeBlocks = botMessage.querySelectorAll('.code-block-container');
        codeBlocks.forEach(block => {
            const preElement = block.querySelector('pre');
            const copyButton = block.querySelector('.copy-button');
            
            if (copyButton && preElement) {
                copyButton.addEventListener('click', () => {
                    // A kód tartalmának kinyerése a <pre> tagből
                    const code = preElement.textContent.trim();
                    
                    navigator.clipboard.writeText(code).then(() => {
                        // Siker esetén a gomb visszajelzése
                        copyButton.innerHTML = '<i class="fas fa-check"></i> Másolva!';
                        setTimeout(() => {
                            copyButton.innerHTML = '<i class="fas fa-copy"></i> Másolás';
                        }, 2000);
                    }).catch(err => {
                        console.error('Sikertelen másolás:', err);
                        alert('A másolás sikertelen. Kérjük, próbálja újra!');
                    });
                });
            }
        });
        
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        }
    })
    .catch(error => {
        console.error('Hiba:', error);
        botMessage.remove();
        const errorMessage = document.createElement('div');
        errorMessage.className = 'chat-message ai-text-message error';
        errorMessage.textContent = '❌ Hiba történt a válasz lekérésekor.';
        chatBox.appendChild(errorMessage);
        chatBox.scrollTop = chatBox.scrollHeight;
    });
}

document.getElementById('chat-form').addEventListener('submit', sendMessage);

document.getElementById('user-input').addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage(event);
    }
});