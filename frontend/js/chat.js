// C:\xampp\htdocs\SmartCodeGen\js\chat.js

document.addEventListener('DOMContentLoaded', () => {
    // Beszélgetések betöltése az oldal betöltésekor
    loadConversations();
});


// *** ÚJ KÓD: Korábbi beszélgetések listájának betöltése ***
function loadConversations() {
    const conversationsList = document.getElementById('conversations-list');
    const formData = new FormData();
    formData.append('action', 'get_conversations');

    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success' && data.conversations.length > 0) {
            conversationsList.innerHTML = ''; // Töröljük a placeholder-t
            data.conversations.forEach(conv => {
                const li = document.createElement('li');
                li.className = 'conversation-item';
                li.dataset.conversationId = conv.conversation_id;
                
                // Beszélgetés címe: az első üzenet első 20 karaktere
                const title = conv.prompt.substring(0, 20) + '...';
                
                li.innerHTML = `
                    <span class="conversation-title">${title}</span>
                    <button class="delete-conversation-button" data-conversation-id="${conv.conversation_id}"><i class="fas fa-trash"></i></button>
                `;
                conversationsList.appendChild(li);
            });
            
            // Eseménykezelők hozzáadása a betöltő és törlő gombokhoz
            conversationsList.querySelectorAll('.conversation-item').forEach(item => {
                item.addEventListener('click', handleConversationClick);
            });
            
            conversationsList.querySelectorAll('.delete-conversation-button').forEach(button => {
                button.addEventListener('click', handleDeleteClick);
            });
            
        } else {
            conversationsList.innerHTML = '<li class="no-conversations-message">Még nincsenek beszélgetéseid.</li>';
        }
    })
    .catch(error => {
        console.error('Hiba a beszélgetések betöltésekor:', error);
    });
}


// *** ÚJ KÓD: Beszélgetés betöltése a chat ablakba ***
function handleConversationClick(event) {
    const conversationId = event.currentTarget.dataset.conversationId;
    const chatBox = document.getElementById('chat-box');

    // Betöltés előtt töröljük a chat ablakot
    chatBox.innerHTML = '';

    const loadingMessage = document.createElement('div');
    loadingMessage.className = 'chat-message ai-text-message';
    loadingMessage.innerHTML = '<span class="typing-indicator">🔄 Beszélgetés betöltése...</span>';
    chatBox.appendChild(loadingMessage);

    const formData = new FormData();
    formData.append('action', 'load_conversation');
    formData.append('conversation_id', conversationId);

    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            chatBox.innerHTML = data.messages;
        } else {
            chatBox.innerHTML = `<div class="chat-message ai-text-message error">❌ Hiba történt a beszélgetés betöltésekor: ${data.message}</div>`;
        }
    })
    .catch(error => {
        console.error('Hiba a beszélgetés betöltésekor:', error);
        chatBox.innerHTML = '<div class="chat-message ai-text-message error">❌ Hálózati hiba a beszélgetés betöltésekor.</div>';
    });
}

// *** ÚJ KÓD: Beszélgetés törlése ***
function handleDeleteClick(event) {
    event.stopPropagation(); // Megakadályozzuk, hogy a szülő (conversation-item) eseménye is lefutjon
    const conversationId = event.currentTarget.dataset.conversationId;

    if (confirm('Biztosan törölni szeretnéd ezt a beszélgetést?')) {
        const formData = new FormData();
        formData.append('action', 'delete_conversation');
        formData.append('conversation_id', conversationId);

        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                loadConversations(); // Újra betöltjük a listát a törlés után
                // Ha az aktuális beszélgetést töröltük, indítunk egy újat
                const chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = '<div class="chat-message ai-text-message">🤖 Üdvözöllek a SmartCodeGen rendszerben! Miben segíthetek?</div>';
            } else {
                alert('Hiba a törlés során: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Hiba a törlés során:', error);
            alert('Hálózati hiba a törlés során.');
        });
    }
}


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

// *** ÚJ KÓD: Beszélgetés újraindítása gomb eseménykezelője ***
document.getElementById('new-chat-button').addEventListener('click', () => {
    if (confirm('Biztosan új beszélgetést szeretnél kezdeni?')) {
        const formData = new FormData();
        formData.append('action', 'clear_chat');

        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = ''; // Kiürítjük a chat ablakot

                // Hozzáadjuk az eredeti üdvözlő üzenetet
                const welcomeMessage = document.createElement('div');
                welcomeMessage.className = 'chat-message ai-text-message';
                welcomeMessage.textContent = '🤖 Üdvözöllek a SmartCodeGen rendszerben! Miben segíthetek?';
                chatBox.appendChild(welcomeMessage);
                
                // Visszatérünk az eredeti állapotba
                loadConversations();
            } else {
                alert('Hiba történt a beszélgetés újraindítása közben.');
            }
        })
        .catch(error => {
            console.error('Hiba:', error);
            alert('Hálózati hiba a beszélgetés újraindításakor.');
        });
    }
});