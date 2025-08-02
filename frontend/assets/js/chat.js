// C:\xampp\htdocs\SmartCodeGen\js\chat.js

function sendMessage(event) {
    event.preventDefault();
    const input = document.getElementById('user-input');
    const languageSelect = document.getElementById('language-select');
    const message = input.value.trim();
    const selectedLanguage = languageSelect.value;

    if (message === '') return;

    const chatBox = document.getElementById('chat-box');

    const userMessage = document.createElement('div');
    userMessage.className = 'chat-message user';
    userMessage.textContent = message;
    chatBox.appendChild(userMessage);

    const botMessage = document.createElement('div');
    botMessage.className = 'chat-message';
    botMessage.textContent = '🤖 Dolgozom a válaszon...';
    chatBox.appendChild(botMessage);

    chatBox.scrollTop = chatBox.scrollHeight;
    input.value = '';

    fetch('http://localhost:8080/api/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            prompt: message,
            language: selectedLanguage
        })
    })
    .then(response => response.text())
    .then(data => {
        botMessage.textContent = '🤖 ' + data;
        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(error => {
        botMessage.textContent = '❌ Hiba történt a válasz lekérésekor.';
        console.error('Hiba:', error);
    });
}