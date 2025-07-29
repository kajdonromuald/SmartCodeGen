
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>SmartCodeGen - Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;900&display=swap');

        :root {
            --accent-color: #8672FF;
            --base-color: white;
            --text-color: #2E2B41;
            --input-color: #F3F0FF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-family: 'Montserrat', sans-serif;
            font-size: 12pt;
            color: var(--text-color);
        }

        body {
            min-height: 100vh;
            background-color: #f0f0f0;
        }

        .navbar {
            width: 100%;
            background-color: var(--accent-color);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .navbar a {
            color: white;
            margin-left: 20px;
            font-weight: bold;
            text-decoration: none;
        }

        .chat-container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .chat-box-container {
            width: 100%;
            max-width: 800px;
            background-color: var(--base-color);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            height: 80vh;
        }

        .chat-box {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            border-bottom: 1px solid #ccc;
        }

        .chat-message {
            margin-bottom: 10px;
        }

        .chat-message.user {
            text-align: right;
        }

        .chat-input {
            display: flex;
            padding: 10px;
            gap: 10px;
        }

        .chat-input input {
            flex-grow: 1;
            padding: 12px 16px;
            font: inherit;
            border: 2px solid var(--accent-color);
            border-radius: 25px;
            background-color: var(--input-color);
        }

        .chat-input button {
            width: 48px;
            height: 48px;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
            cursor: pointer;
        }

        .chat-input button:hover {
            background-color: var(--text-color);
            transform: scale(1.1);
        }

        @media (max-width: 600px) {
            .chat-box-container {
                height: 90vh;
                margin: 0 10px;
            }

            .chat-input input {
                font-size: 1rem;
            }

            .chat-input button {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>SmartCodeGen</h2>
        <div>
            <a href="index.php">Chat</a>
            <a href="prototype.php">Prototípus</a>
            <a href="logout.php">Kijelentkezés</a>
        </div>
    </div>
    <div class="chat-container">
        <div class="chat-box-container">
            <div class="chat-box" id="chat-box">
                <div class="chat-message">🤖 Üdvözöllek a SmartCodeGen rendszerben! Miben segíthetek?</div>
            </div>
            <form class="chat-input" onsubmit="sendMessage(event)">
                <input type="text" id="user-input" placeholder="Írd be az üzeneted..." required>
                <button type="submit">➤</button>
            </form>
        </div>
    </div>

    <script>
        function sendMessage(event) {
    event.preventDefault();
    const input = document.getElementById('user-input');
    const message = input.value.trim();
    if (message === '') return;

    const chatBox = document.getElementById('chat-box');

    // Felhasználói üzenet megjelenítése
    const userMessage = document.createElement('div');
    userMessage.className = 'chat-message user';
    userMessage.textContent = message;
    chatBox.appendChild(userMessage);

    // Válasz betöltése
    const botMessage = document.createElement('div');
    botMessage.className = 'chat-message';
    botMessage.textContent = '🤖 Dolgozom a válaszon...';
    chatBox.appendChild(botMessage);

    chatBox.scrollTop = chatBox.scrollHeight;
    input.value = '';

    // 🔗 Fetch a Java backendhez
    fetch('http://localhost/SmartCodeGen/frontend/index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            prompt: message,
            language: 'JAVA' // vagy 'PYTHON', 'JAVASCRIPT'
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

    </script>
</body>
</html>
