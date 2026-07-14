<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niche Dating App</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Niche Dating</h1>

            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Login</h2>
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-email">Email</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="login-email" type="email" placeholder="Email">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-password">Password</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="login-password" type="password" placeholder="******************">
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                            Sign In
                        </button>
                    </div>
                </form>
            </div>

            <hr class="my-6 border-gray-300">

            <div>
                <h2 class="text-xl font-semibold mb-4">Register</h2>
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-role">I am a...</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="register-role">
                            <option value="seeker">Seeker</option>
                            <option value="provider">Provider</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-username">Username</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="register-username" type="text" placeholder="Username">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-email">Email</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="register-email" type="email" placeholder="Email">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-password">Password</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="register-password" type="password" placeholder="******************">
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                            Register
                        </button>
                    </div>
                </form>
            </div>

            <!-- Chat Widget -->
            <div id="chat-widget" class="mt-8 border-t pt-6" style="display: none;">
                <h2 class="text-xl font-semibold mb-4">Chat</h2>
                <div id="messages-list" class="h-48 overflow-y-auto mb-4 p-2 border rounded bg-gray-50">
                    <!-- Messages will appear here -->
                </div>
                <div class="flex">
                    <input type="text" id="chat-input" class="flex-1 shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Type a message...">
                    <button id="send-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r focus:outline-none focus:shadow-outline">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Stub for dynamically displaying messages using Echo and Pusher-js
        document.addEventListener('DOMContentLoaded', () => {
            const chatWidget = document.getElementById('chat-widget');
            const messagesList = document.getElementById('messages-list');
            const chatInput = document.getElementById('chat-input');
            const sendButton = document.getElementById('send-button');

            // Assume user is logged in for the stub and we have their ID
            const currentUserId = 1; // Stub ID

            // Display widget
            chatWidget.style.display = 'block';

            // Example of how Laravel Echo would be used to listen
            if (window.Echo) {
                window.Echo.private(`chat.${currentUserId}`)
                    .listen('MessageSent', (e) => {
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('mb-2', 'p-2', 'bg-white', 'rounded', 'shadow-sm');
                        messageElement.textContent = `Received: ${e.message}`;
                        messagesList.appendChild(messageElement);
                        messagesList.scrollTop = messagesList.scrollHeight;
                    });
            }

            sendButton.addEventListener('click', () => {
                if (chatInput.value.trim() !== '') {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('mb-2', 'p-2', 'bg-blue-100', 'text-right', 'rounded', 'shadow-sm');
                    messageElement.textContent = `Sent: ${chatInput.value}`;
                    messagesList.appendChild(messageElement);
                    messagesList.scrollTop = messagesList.scrollHeight;
                    chatInput.value = '';
                }
            });
        });

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }, err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
</body>
</html>
