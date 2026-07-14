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

        <section id="authenticated-dashboard" class="hidden w-full max-w-6xl px-4 pb-10" aria-labelledby="discovery-heading">
            <div class="rounded-3xl bg-white p-5 shadow-xl sm:p-8">
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-pink-500">Discover</p>
                        <h2 id="discovery-heading" class="mt-1 text-2xl font-bold text-gray-900 sm:text-3xl">People you may like</h2>
                    </div>
                    <p class="text-sm text-gray-500">Fresh profiles, chosen for you.</p>
                </div>

                <div id="discovery-feed" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" aria-live="polite">
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6 text-center text-sm text-gray-500">Finding profiles near you…</div>
                </div>
            </div>
        </section>
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

            const dashboard = document.getElementById('authenticated-dashboard');
            const discoveryFeed = document.getElementById('discovery-feed');

            const sanitisedToken = [
                localStorage.getItem('access_token'),
                localStorage.getItem('auth_token'),
                localStorage.getItem('sanctum_token'),
            ].find(Boolean);

            const calculateAge = (birthDate) => {
                if (!birthDate) {
                    return null;
                }

                const birthday = new Date(birthDate);

                if (Number.isNaN(birthday.getTime())) {
                    return null;
                }

                const today = new Date();
                let age = today.getFullYear() - birthday.getFullYear();
                const hasNotHadBirthday =
                    today.getMonth() < birthday.getMonth()
                    || (today.getMonth() === birthday.getMonth() && today.getDate() < birthday.getDate());

                return hasNotHadBirthday ? age - 1 : age;
            };

            const createActionButton = (label, classes, profile, action) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `flex-1 rounded-xl px-4 py-2.5 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2 ${classes}`;
                button.textContent = label;
                button.addEventListener('click', () => {
                    // Match actions will be connected to the match API in a follow-up feature.
                    console.info(`${action} selected for profile`, profile.id);
                });

                return button;
            };

            const profileCard = (profile) => {
                const card = document.createElement('article');
                card.className = 'flex min-h-72 flex-col rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg';

                const header = document.createElement('div');
                header.className = 'flex items-start justify-between gap-3';

                const profileDetails = document.createElement('div');
                const name = document.createElement('h3');
                name.className = 'text-xl font-bold text-gray-900';
                const age = calculateAge(profile.birth_date);
                name.textContent = age === null ? profile.username : `${profile.username}, ${age}`;
                profileDetails.appendChild(name);

                if (profile.is_verified) {
                    const verified = document.createElement('span');
                    verified.className = 'mt-2 inline-flex items-center rounded-full bg-pink-100 px-2.5 py-1 text-xs font-bold text-pink-700';
                    verified.textContent = 'Verified';
                    profileDetails.appendChild(verified);
                }

                header.appendChild(profileDetails);
                card.appendChild(header);

                const bio = document.createElement('p');
                bio.className = 'mt-5 flex-1 text-sm leading-6 text-gray-600';
                bio.textContent = profile.bio || 'No bio yet — say hello to learn more.';
                card.appendChild(bio);

                const location = document.createElement('p');
                location.className = 'mt-4 text-sm font-medium text-gray-500';
                location.textContent = profile.location ? `📍 ${profile.location}` : '📍 Location not shared';
                card.appendChild(location);

                const actions = document.createElement('div');
                actions.className = 'mt-5 flex gap-3';
                actions.appendChild(createActionButton('Pass', 'border border-gray-200 bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-300', profile, 'Pass'));
                actions.appendChild(createActionButton('Like', 'bg-pink-500 text-white hover:bg-pink-600 focus:ring-pink-400', profile, 'Like'));
                card.appendChild(actions);

                return card;
            };

            const renderEmptyState = () => {
                discoveryFeed.replaceChildren();
                const emptyState = document.createElement('div');
                emptyState.className = 'col-span-full rounded-2xl border border-dashed border-pink-200 bg-pink-50 px-6 py-12 text-center';
                emptyState.textContent = "You've explored everyone nearby! Check back later.";
                discoveryFeed.appendChild(emptyState);
            };

            const loadDiscovery = async (url = '/api/discover') => {
                const headers = { Accept: 'application/json' };

                if (sanitisedToken) {
                    headers.Authorization = `Bearer ${sanitisedToken}`;
                }

                try {
                    const response = await fetch(url, {
                        headers,
                        credentials: 'same-origin',
                    });

                    if (response.status === 401) {
                        return;
                    }

                    if (!response.ok) {
                        throw new Error(`Discovery request failed with status ${response.status}`);
                    }

                    const payload = await response.json();
                    const profiles = Array.isArray(payload.data) ? payload.data : [];

                    dashboard.classList.remove('hidden');
                    discoveryFeed.replaceChildren();

                    if (profiles.length === 0) {
                        renderEmptyState();
                        return;
                    }

                    profiles.forEach((profile) => discoveryFeed.appendChild(profileCard(profile)));

                    if (payload.links?.next) {
                        const loadMore = document.createElement('button');
                        loadMore.type = 'button';
                        loadMore.className = 'col-span-full rounded-xl border border-pink-200 bg-white px-4 py-3 text-sm font-semibold text-pink-600 transition hover:bg-pink-50 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-offset-2';
                        loadMore.textContent = 'Load more profiles';
                        loadMore.addEventListener('click', () => loadDiscovery(payload.links.next));
                        discoveryFeed.appendChild(loadMore);
                    }
                } catch (error) {
                    console.error('Unable to load discovery profiles.', error);
                    dashboard.classList.remove('hidden');
                    discoveryFeed.replaceChildren();

                    const failure = document.createElement('div');
                    failure.className = 'col-span-full rounded-2xl border border-red-100 bg-red-50 px-6 py-8 text-center text-sm text-red-700';
                    failure.textContent = 'We could not load discovery right now. Please try again shortly.';
                    discoveryFeed.appendChild(failure);
                }
            };

            loadDiscovery();
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
