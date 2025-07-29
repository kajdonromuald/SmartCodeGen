<?php
session_start();
if (!isset($_SESSION['jwt'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>SmartCodeGen - Chat</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .navbar {
            width: 100%;
            background-color: var(--accent-color);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }

        .navbar h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .navbar a {
            color: white;
            margin-left: 20px;
            font-weight: bold;
        }

        .chat-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 60px);
            padding: 20px;
            box-sizing: border-box;
        }

        .chat-box-container {
            width: 100%;
            max-width: 800px;
            background-color: var(--base-color);
            border: 2px solid var(--accent-color);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .chat-box {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
            background-color: var(--input-color);
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .chat-message {
            margin-bottom: 10px;
            text-align: left;
        }

        .chat-message.user {
            text-align: right;
        }

        .chat-input {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-input input {
            flex-grow: 1;
            padding: 10px 15px;
            font: inherit;
            border: 2px solid var(--accent-color);
            border-radius: 20px;
            background-color: var(--base-color);
        }

        .chat-input button {
            background-color: var(--accent-color);
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 50%;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .chat-input button:hover {
            background-color: var(--text-color);
        }

        .chat-input button:active {
            transform: scale(1.2);
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
    <div class="chat-wrapper">
        <div class="chat-box-container">
            <div class="chat-box" id="chat-box">
                <div class="chat-message">🤖 Üdvözöllek a SmartCodeGen rendszerben! Miben segíthetek?</div>
            </div>
            <form class="chat-input" onsubmit="sendMessage(event)">
                <input type="text" id="user-input" placeholder="Írd be az üzeneted..." required>
                <button type="submit" title="Küldés">➤</button>
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
        }
    </script>
</body>
</html>
