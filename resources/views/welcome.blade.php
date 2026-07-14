<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niche Midnight</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes match-in { from { opacity: 0; transform: scale(.92); } to { opacity: 1; transform: scale(1); } }
        .animate-match-in { animation: match-in 280ms cubic-bezier(.16, 1, .3, 1) both; }
    </style>
</head>
<body class="bg-slate-950 font-sans text-slate-100 antialiased">
    <section id="welcome-gate" class="fixed inset-0 z-40 flex items-center justify-center bg-slate-950 p-5">
        <div class="w-full max-w-sm rounded-3xl border border-slate-800/50 bg-slate-900 p-6 shadow-2xl shadow-black/50">
            <p class="text-xs font-bold uppercase tracking-[.28em] text-emerald-400">Niche Midnight</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-100">Meet your next spark.</h1>
            <p class="mt-2 text-sm leading-6 text-slate-400">A quieter, more intentional kind of connection.</p>
            <div class="mt-6 grid grid-cols-2 rounded-xl bg-slate-950 p-1">
                <button type="button" data-auth-mode="login" class="auth-mode rounded-lg bg-cyan-300 px-3 py-2 text-sm font-bold text-slate-950">Sign In</button>
                <button type="button" data-auth-mode="register" class="auth-mode rounded-lg px-3 py-2 text-sm font-bold text-slate-400">Sign Up</button>
            </div>
            <form id="login-form" class="mt-6 space-y-4">
                <input id="gate-login-email" type="email" required placeholder="Email" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none placeholder:text-slate-600 focus:border-cyan-300">
                <input id="gate-login-password" type="password" required placeholder="Password" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none placeholder:text-slate-600 focus:border-cyan-300">
                <button type="submit" class="w-full rounded-xl bg-cyan-300 px-4 py-3 font-bold text-slate-950 transition hover:bg-cyan-200">Sign In</button>
            </form>
            <form id="register-form" class="mt-6 hidden space-y-3">
                <select id="register-role" required class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-cyan-300"><option value="seeker">I am a Seeker</option><option value="provider">I am a Provider</option></select>
                <input id="register-username" type="text" required placeholder="Username" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none placeholder:text-slate-600 focus:border-cyan-300">
                <input id="register-email" type="email" required placeholder="Email" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none placeholder:text-slate-600 focus:border-cyan-300">
                <input id="register-password" type="password" required minlength="8" placeholder="Password (8+ characters)" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none placeholder:text-slate-600 focus:border-cyan-300">
                <input id="register-location" type="text" required placeholder="City or location" class="w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none placeholder:text-slate-600 focus:border-cyan-300">
                <button type="submit" class="w-full rounded-xl bg-emerald-400 px-4 py-3 font-bold text-slate-950 transition hover:bg-emerald-300">Create account</button>
            </form>
            <p id="auth-error" class="mt-4 hidden rounded-xl border border-red-500/30 bg-red-950/20 px-3 py-2 text-sm text-red-300" role="alert"></p>
        </div>
    </section>

    <div id="app-shell" class="relative mx-auto hidden h-screen max-w-md flex-col justify-between overflow-hidden bg-slate-950 text-slate-100 shadow-2xl shadow-black/70">
        <header class="flex items-center justify-between border-b border-slate-800/50 px-5 py-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[.28em] text-emerald-400">Niche Midnight</p>
                <h1 class="mt-1 text-xl font-bold tracking-tight">Find your spark</h1>
            </div>
            <span class="flex h-9 w-9 items-center justify-center rounded-full border border-cyan-300/20 bg-cyan-300/10 text-cyan-200">✦</span>
        </header>

        <main class="relative min-h-0 flex-1 overflow-hidden">
            <section id="discovery-view" class="h-full px-5 py-5" data-view="discovery">
                <div class="flex h-full flex-col">
                    <div class="mb-3 flex items-end justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[.2em] text-cyan-300">Discovery deck</p>
                            <h2 class="mt-1 text-2xl font-bold">Made for tonight</h2>
                        </div>
                        <span id="discovery-status" class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-300">Live</span>
                    </div>
                    <div id="discovery-stage" class="relative flex flex-1 items-center justify-center" aria-live="polite">
                        <p class="rounded-2xl border border-slate-800/50 bg-slate-900 px-6 py-8 text-center text-sm text-slate-400">Sign in to start discovering.</p>
                    </div>
                </div>
            </section>

            <section id="matches-view" class="hidden h-full overflow-hidden px-5 py-5" data-view="matches">
                <div class="flex h-full flex-col">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-400">Matches & chat</p>
                        <h2 class="mt-1 text-2xl font-bold">Your connections</h2>
                        <div id="active-matches-list" class="mt-4 flex gap-3 overflow-x-auto pb-3" aria-label="Active matches">
                            <p class="text-sm text-slate-500">New mutual matches appear here.</p>
                        </div>
                    </div>
                    <div id="chat-panel" class="mt-2 flex min-h-0 flex-1 flex-col rounded-3xl border border-slate-800/50 bg-slate-900 p-4 shadow-xl shadow-black/30">
                        <div class="flex items-center gap-3 border-b border-slate-800/70 pb-3">
                            <span id="active-chat-avatar" class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-cyan-300/70 bg-cyan-300/10 text-sm font-bold text-cyan-100">?</span>
                            <div>
                                <p id="active-chat-name" class="font-semibold">Select a match</p>
                                <p class="text-xs text-emerald-400">Private Reverb chat</p>
                            </div>
                        </div>
                        <div id="messages-list" class="min-h-0 flex-1 space-y-2 overflow-y-auto py-4 text-sm"></div>
                        <div class="mt-2 flex gap-2 border-t border-slate-800/70 pt-3">
                            <input id="chat-input" type="text" placeholder="Write a message…" class="min-w-0 flex-1 rounded-xl border border-slate-700 bg-slate-950 px-3 py-3 text-sm text-slate-100 outline-none placeholder:text-slate-600 focus:border-cyan-300">
                            <button id="send-button" type="button" class="rounded-xl bg-cyan-300 px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-cyan-200 disabled:opacity-50">Send</button>
                        </div>
                    </div>
                </div>
            </section>

            <section id="profile-view" class="hidden h-full overflow-y-auto px-5 py-6" data-view="profile">
                <div id="account-settings" class="rounded-3xl border border-slate-800/50 bg-slate-900 p-5 shadow-xl shadow-black/30">
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-400">My profile</p>
                    <h2 id="profile-name" class="mt-2 text-2xl font-bold">Your Midnight profile</h2>
                    <p id="profile-location" class="mt-2 text-sm text-slate-400">Manage your discovery profile and preferences.</p>
                    <button id="edit-profile-button" type="button" class="mt-5 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm font-semibold text-slate-300">Edit profile</button>
                    <button id="logout-button" type="button" class="mt-3 w-full rounded-xl border border-red-500/50 px-4 py-3 text-sm font-bold text-red-400 transition hover:bg-red-950/20">Log out</button>
                </div>
                <div id="edit-profile-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/80 p-4 backdrop-blur-sm">
                    <div class="w-full max-w-sm rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-black/50">
                        <h3 class="mb-4 text-xl font-bold text-slate-100">Edit Profile</h3>
                        <form id="edit-profile-form" enctype="multipart/form-data" class="space-y-4">
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-400">Profile Photo</label>
                                <input type="file" id="profile-photo-input" name="photo" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:rounded-full file:border-0 file:bg-blue-950 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-400 hover:file:bg-blue-900">
                            </div>
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-400">Username</label>
                                <input type="text" id="profile-username-input" name="username" class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-400">Location</label>
                                <input type="text" id="profile-location-input" name="location" class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500">
                            </div>
                            <p id="edit-profile-error" class="hidden text-sm text-red-400" role="alert"></p>
                            <div class="flex space-x-3 pt-2">
                                <button type="button" id="close-profile-modal" class="w-1/2 rounded-xl bg-slate-800 py-3 font-medium text-slate-300 transition hover:bg-slate-700">Cancel</button>
                                <button type="submit" class="w-1/2 rounded-xl bg-blue-600 py-3 font-medium text-white transition hover:bg-blue-500">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>

        <nav class="grid grid-cols-3 border-t border-slate-800/60 bg-slate-900 px-3 py-2" aria-label="Main navigation">
            <button type="button" data-tab="discovery" class="nav-tab flex flex-col items-center gap-1 rounded-xl py-2 text-xs font-semibold text-cyan-300" aria-current="page">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m12 21 1.6-1.46C18.4 15.2 21.5 12.38 21.5 8.91 21.5 6.09 19.3 4 16.5 4c-1.58 0-3.1.74-4.1 1.9C11.4 4.74 9.88 4 8.3 4 5.5 4 3.3 6.09 3.3 8.91c0 3.47 3.1 6.29 7.1 9.93L12 21Z" /></svg>
                Discovery
            </button>
            <button type="button" data-tab="matches" class="nav-tab flex flex-col items-center gap-1 rounded-xl py-2 text-xs font-semibold text-slate-400 transition hover:text-cyan-300">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75h6.75m-6.75 3h4.5m-6.69 6.082A9.75 9.75 0 1 0 3.75 15.75l-1.5 4.5 4.185-1.395Z" /></svg>
                Matches
            </button>
            <button type="button" data-tab="profile" class="nav-tab flex flex-col items-center gap-1 rounded-xl py-2 text-xs font-semibold text-slate-400 transition hover:text-cyan-300">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.118a7.5 7.5 0 0 1 15 0A17.933 17.933 0 0 1 12 21.75a17.933 17.933 0 0 1-7.5-1.632Z" /></svg>
                Profile
            </button>
        </nav>
    </div>

    <div id="match-overlay" class="fixed inset-0 z-50 hidden flex-col items-center justify-center bg-slate-950/98 p-6 text-center" role="dialog" aria-modal="true" aria-labelledby="match-title">
        <div class="w-full max-w-sm animate-match-in">
            <p class="text-xs font-bold uppercase tracking-[.28em] text-cyan-300">The energy is mutual</p>
            <h2 id="match-title" class="mt-3 animate-pulse text-4xl font-black tracking-tight text-emerald-300">It's a Mutual Match!</h2>
            <div class="mt-10 flex items-center justify-center gap-5">
                <div class="flex flex-col items-center gap-2"><span id="current-avatar" class="flex h-24 w-24 items-center justify-center rounded-full bg-slate-800 text-2xl font-black text-cyan-100 ring-4 ring-cyan-300 ring-offset-4 ring-offset-slate-950 shadow-[0_0_30px_rgba(34,211,238,.45)]">Y</span><span class="text-xs text-slate-400">You</span></div>
                <span class="text-3xl text-emerald-300">✦</span>
                <div class="flex flex-col items-center gap-2"><span id="matched-avatar" class="flex h-24 w-24 items-center justify-center rounded-full bg-slate-800 text-2xl font-black text-emerald-200 ring-4 ring-emerald-400 ring-offset-4 ring-offset-slate-950 shadow-[0_0_30px_rgba(52,211,153,.45)]">M</span><span id="matched-name" class="max-w-24 truncate text-xs text-slate-400">Your match</span></div>
            </div>
            <button id="send-message-handoff" type="button" class="mt-10 w-full rounded-2xl bg-cyan-300 px-5 py-4 text-base font-black text-slate-950 transition hover:bg-cyan-200">Send Message</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('auth_token');
            const welcomeGate = document.getElementById('welcome-gate');
            const appShell = document.getElementById('app-shell');
            const views = {
                discovery: document.getElementById('discovery-view'),
                matches: document.getElementById('matches-view'),
                profile: document.getElementById('profile-view'),
            };
            const navTabs = document.querySelectorAll('.nav-tab');
            const stage = document.getElementById('discovery-stage');
            const status = document.getElementById('discovery-status');
            const overlay = document.getElementById('match-overlay');
            const activeMatchesList = document.getElementById('active-matches-list');
            const messagesList = document.getElementById('messages-list');
            const chatInput = document.getElementById('chat-input');
            const sendButton = document.getElementById('send-button');
            const activeChatName = document.getElementById('active-chat-name');
            const activeChatAvatar = document.getElementById('active-chat-avatar');
            const accountSettings = document.getElementById('account-settings');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const authModeButtons = document.querySelectorAll('.auth-mode');
            const authError = document.getElementById('auth-error');
            const logoutButton = document.getElementById('logout-button');
            const editProfileButton = document.getElementById('edit-profile-button');
            const editProfileModal = document.getElementById('edit-profile-modal');
            const closeProfileModal = document.getElementById('close-profile-modal');
            const editProfileForm = document.getElementById('edit-profile-form');
            const editProfileError = document.getElementById('edit-profile-error');
            let queue = [];
            let nextPage = null;
            let fetching = false;
            let currentUser = null;
            let activeMatch = null;
            let realtimeUserId = null;
            const matches = [];

            const headers = (json = false) => {
                const result = { Accept: 'application/json' };
                if (json) result['Content-Type'] = 'application/json';
                if (token) result.Authorization = `Bearer ${token}`;
                return result;
            };

            const showView = (name) => {
                Object.entries(views).forEach(([key, view]) => view.classList.toggle('hidden', key !== name));
                navTabs.forEach((tab) => {
                    const active = tab.dataset.tab === name;
                    tab.classList.toggle('text-cyan-300', active);
                    tab.classList.toggle('text-slate-400', !active);
                    tab.toggleAttribute('aria-current', active);
                });
            };
            navTabs.forEach((tab) => tab.addEventListener('click', () => showView(tab.dataset.tab)));

            const ageFor = (birthDate) => {
                if (!birthDate) return null;
                const date = new Date(birthDate);
                if (Number.isNaN(date.getTime())) return null;
                const today = new Date();
                let age = today.getFullYear() - date.getFullYear();
                if (today.getMonth() < date.getMonth() || (today.getMonth() === date.getMonth() && today.getDate() < date.getDate())) age -= 1;
                return age;
            };

            const emptyDeck = (message) => {
                stage.replaceChildren();
                const state = document.createElement('p');
                state.className = 'rounded-2xl border border-dashed border-cyan-300/25 bg-slate-900 px-6 py-8 text-center text-sm leading-6 text-slate-400';
                state.textContent = message;
                stage.appendChild(state);
            };

            const photoSlots = [
                'radial-gradient(circle at 75% 15%, rgba(34,211,238,.45), transparent 32%), linear-gradient(145deg, #162236, #0f172a 55%, #032d3a)',
                'radial-gradient(circle at 20% 20%, rgba(52,211,153,.4), transparent 30%), linear-gradient(145deg, #0b2531, #111827 58%, #172554)',
                'radial-gradient(circle at 75% 80%, rgba(34,211,238,.32), transparent 32%), linear-gradient(145deg, #1e293b, #0f172a 60%, #123341)',
            ];

            const renderCard = () => {
                if (!queue.length) {
                    if (nextPage) loadNextPage(); else emptyDeck("You've explored everyone nearby! Check back later.");
                    return;
                }
                const profile = queue[0];
                let photoIndex = 0;
                let startX = 0;
                let deltaX = 0;
                let dragging = false;
                let moved = false;
                stage.replaceChildren();
                const wrapper = document.createElement('div');
                wrapper.className = 'w-full max-w-sm';
                const card = document.createElement('article');
                card.className = 'swipe-card relative aspect-[3/4] w-full cursor-grab select-none overflow-hidden rounded-[2rem] border border-slate-700/70 bg-slate-900 shadow-2xl shadow-black/60 active:cursor-grabbing';
                card.style.touchAction = 'none';
                const media = document.createElement('div');
                media.className = 'absolute inset-0 transition-[background] duration-300';
                const dashes = document.createElement('div');
                dashes.className = 'absolute inset-x-4 top-4 z-10 flex gap-1';
                const content = document.createElement('div');
                content.className = 'absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950 via-slate-950/85 to-transparent px-6 pb-7 pt-24';
                const name = document.createElement('h3');
                name.className = 'text-3xl font-black tracking-tight text-slate-100';
                const age = ageFor(profile.birth_date);
                name.textContent = age === null ? profile.username : `${profile.username}, ${age}`;
                content.appendChild(name);
                if (profile.is_verified) {
                    const badge = document.createElement('span');
                    badge.className = 'mt-3 inline-flex rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-300';
                    badge.textContent = 'Verified';
                    content.appendChild(badge);
                }
                const bio = document.createElement('p');
                bio.className = 'mt-3 text-sm leading-6 text-slate-300';
                bio.textContent = profile.bio || 'No bio yet — say hello to learn more.';
                content.appendChild(bio);
                const location = document.createElement('p');
                location.className = 'mt-3 text-sm font-medium text-slate-400';
                location.textContent = profile.location ? `⌖ ${profile.location}` : '⌖ Location not shared';
                content.appendChild(location);
                card.append(media, dashes, content);
                const actions = document.createElement('div');
                actions.className = 'mt-5 flex justify-center gap-8';
                const makeAction = (label, symbol, classes, outcome, direction) => {
                    const button = document.createElement('button');
                    button.type = 'button'; button.textContent = symbol; button.setAttribute('aria-label', label);
                    button.className = `flex h-14 w-14 items-center justify-center rounded-full border text-2xl shadow-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-950 ${classes}`;
                    button.addEventListener('click', () => flyAway(outcome, direction));
                    return button;
                };
                actions.append(makeAction('Pass', '×', 'border-slate-700 bg-slate-900 text-slate-300 hover:bg-slate-800 focus:ring-slate-400', 'declined', -1));
                actions.append(makeAction('Like', '♥', 'border-cyan-300/50 bg-cyan-300 text-slate-950 hover:bg-cyan-200 focus:ring-cyan-200', 'accepted', 1));
                wrapper.append(card, actions);
                stage.appendChild(wrapper);

                const updateMedia = () => {
                    const photos = Array.isArray(profile.photos) && profile.photos.length ? profile.photos : null;
                    const slotCount = photos ? photos.length : photoSlots.length;
                    photoIndex = (photoIndex + slotCount) % slotCount;
                    media.style.background = photos ? `center / cover no-repeat url("${photos[photoIndex]}")` : photoSlots[photoIndex];
                    dashes.replaceChildren();
                    Array.from({ length: slotCount }, (_, index) => {
                        const dash = document.createElement('span');
                        dash.className = `h-1 flex-1 rounded-full ${index === photoIndex ? 'bg-slate-100' : 'bg-slate-100/35'}`;
                        dashes.appendChild(dash);
                    });
                };

                const resetCard = () => { card.style.transition = 'transform 220ms ease-out'; card.style.transform = 'translateX(0) rotate(0deg)'; };
                const flyAway = async (outcome, direction) => {
                    if (card.dataset.swiping === 'true') return;
                    card.dataset.swiping = 'true';
                    card.style.transition = 'transform 260ms cubic-bezier(.2,.9,.2,1), opacity 260ms ease-out';
                    card.style.transform = `translateX(${direction * 140}vw) rotate(${direction * 28}deg)`;
                    card.style.opacity = '0';
                    window.setTimeout(async () => {
                        try {
                            const response = await fetch('/api/matches', { method: 'POST', headers: headers(true), credentials: 'same-origin', body: JSON.stringify({ user_two_id: profile.id, status: outcome }) });
                            const result = await response.json();
                            if (!response.ok) throw new Error(result.message || 'Unable to save your swipe.');
                            queue.shift();
                            if (queue.length < 3) loadNextPage();
                            renderCard();
                            if (result.mutual_match === true) openMatchOverlay(profile);
                        } catch (error) {
                            console.error('Unable to save swipe.', error);
                            card.dataset.swiping = 'false'; card.style.opacity = '1'; resetCard();
                        }
                    }, 180);
                };
                const dragMove = (clientX) => {
                    if (!dragging || card.dataset.swiping === 'true') return;
                    deltaX = clientX - startX; moved ||= Math.abs(deltaX) > 8;
                    card.style.transition = 'none';
                    card.style.transform = `translateX(${deltaX}px) rotate(${deltaX / 18}deg)`;
                };
                const dragEnd = (clientX) => {
                    if (!dragging) return;
                    dragging = false; deltaX = clientX - startX;
                    if (Math.abs(deltaX) > 120) flyAway(deltaX > 0 ? 'accepted' : 'declined', deltaX > 0 ? 1 : -1);
                    else if (!moved) { const rect = card.getBoundingClientRect(); photoIndex += clientX < rect.left + rect.width / 2 ? -1 : 1; updateMedia(); resetCard(); }
                    else resetCard();
                };
                card.addEventListener('mousedown', (event) => { dragging = true; moved = false; startX = event.clientX; });
                card.addEventListener('mousemove', (event) => dragMove(event.clientX));
                card.addEventListener('mouseup', (event) => dragEnd(event.clientX));
                card.addEventListener('mouseleave', () => { if (dragging) { dragging = false; resetCard(); } });
                card.addEventListener('touchstart', (event) => { dragging = true; moved = false; startX = event.touches[0].clientX; }, { passive: true });
                card.addEventListener('touchmove', (event) => { event.preventDefault(); dragMove(event.touches[0].clientX); }, { passive: false });
                card.addEventListener('touchend', (event) => dragEnd(event.changedTouches[0].clientX));
                updateMedia();
            };

            const fetchDiscovery = async (url = '/api/discover', replace = false) => {
                if (!token || fetching) return;
                fetching = true;
                try {
                    const response = await fetch(url, { headers: headers(), credentials: 'same-origin' });
                    if (!response.ok) throw new Error(`Discovery request failed: ${response.status}`);
                    const payload = await response.json();
                    const received = Array.isArray(payload.data) ? payload.data : [];
                    queue = replace ? received : [...queue, ...received];
                    nextPage = payload.links?.next ?? null;
                    status.textContent = 'Live';
                    renderCard();
                } catch (error) {
                    console.error('Unable to load discovery.', error);
                    emptyDeck('Discovery is unavailable right now. Please try again shortly.');
                } finally { fetching = false; }
            };
            const loadNextPage = () => { if (nextPage && !fetching) fetchDiscovery(nextPage); };

            const renderActiveMatches = () => {
                activeMatchesList.replaceChildren();
                if (!matches.length) { activeMatchesList.innerHTML = '<p class="text-sm text-slate-500">New mutual matches appear here.</p>'; return; }
                matches.forEach((profile) => {
                    const button = document.createElement('button');
                    const selected = activeMatch?.id === profile.id;
                    button.type = 'button'; button.textContent = profile.username;
                    button.className = `shrink-0 rounded-full border px-4 py-2 text-sm font-semibold transition ${selected ? 'border-cyan-300/60 bg-cyan-300/10 text-cyan-100' : 'border-slate-700 bg-slate-950 text-slate-400'}`;
                    button.addEventListener('click', () => selectMatch(profile));
                    activeMatchesList.appendChild(button);
                });
            };
            const appendMessage = (message, outgoing) => {
                const bubble = document.createElement('div');
                bubble.className = `max-w-[85%] rounded-xl p-3 ${outgoing ? 'ml-auto bg-cyan-300/10 text-right text-cyan-100' : 'bg-slate-800 text-slate-200'}`;
                bubble.textContent = message.message;
                messagesList.appendChild(bubble); messagesList.scrollTop = messagesList.scrollHeight;
            };
            const selectMatch = async (profile) => {
                activeMatch = profile;
                activeChatName.textContent = profile.username;
                activeChatAvatar.textContent = profile.username.charAt(0).toUpperCase();
                renderActiveMatches(); messagesList.replaceChildren();
                try {
                    const response = await fetch(`/api/messages/${profile.id}`, { headers: headers(), credentials: 'same-origin' });
                    if (!response.ok) return;
                    const payload = await response.json();
                    const messages = Array.isArray(payload.data) ? payload.data : [];
                    if (activeMatch?.id !== profile.id) return;
                    if (!messages.length) {
                        const starter = document.createElement('p');
                        starter.className = 'py-5 text-center text-sm text-slate-500';
                        starter.textContent = `You matched with ${profile.username}. Send the first message.`;
                        messagesList.appendChild(starter);
                        return;
                    }
                    messages.forEach((message) => appendMessage(message, message.sender_id === currentUser?.id));
                } catch (error) { console.error('Unable to load conversation.', error); }
            };
            const openMatchOverlay = (profile) => {
                if (!matches.some((match) => match.id === profile.id)) matches.push(profile);
                selectMatch(profile);
                document.getElementById('matched-avatar').textContent = profile.username.charAt(0).toUpperCase();
                document.getElementById('matched-name').textContent = profile.username;
                overlay.classList.remove('hidden'); overlay.classList.add('flex');
            };
            document.getElementById('send-message-handoff').addEventListener('click', () => {
                overlay.classList.add('hidden'); overlay.classList.remove('flex'); showView('matches');
                if (activeMatch) selectMatch(activeMatch);
                chatInput.focus();
            });
            sendButton.addEventListener('click', async () => {
                const message = chatInput.value.trim(); if (!message || !activeMatch) return;
                sendButton.disabled = true;
                try {
                    const response = await fetch('/api/messages', { method: 'POST', headers: headers(true), credentials: 'same-origin', body: JSON.stringify({ receiver_id: activeMatch.id, message }) });
                    const payload = await response.json(); if (!response.ok) throw new Error(payload.message || 'Unable to send message.');
                    appendMessage(payload.data ?? payload, true); chatInput.value = '';
                } catch (error) { console.error('Unable to send message.', error); } finally { sendButton.disabled = false; }
            });

            const subscribeToReverb = (userId) => {
                if (!userId || realtimeUserId === userId) return;
                if (!window.Echo) { window.setTimeout(() => subscribeToReverb(userId), 150); return; }
                realtimeUserId = userId;
                window.Echo.private(`chat.${userId}`).listen('MessageSent', (message) => {
                    if (activeMatch && (message.sender_id === activeMatch.id || message.receiver_id === activeMatch.id)) appendMessage(message, message.sender_id === currentUser?.id);
                });
            };
            const updateProfileSummary = (user) => {
                currentUser = user;
                document.getElementById('profile-name').textContent = currentUser.username;
                document.getElementById('profile-location').textContent = currentUser.location || 'Location not shared';
                document.getElementById('current-avatar').textContent = currentUser.username.charAt(0).toUpperCase();
            };
            const loadProfile = async () => {
                if (!token) return;
                try {
                    const response = await fetch('/api/profile', { headers: headers(), credentials: 'same-origin' });
                    if (!response.ok) return;
                    const payload = await response.json();
                    updateProfileSummary(payload.data ?? payload);
                    subscribeToReverb(currentUser.id);
                } catch (error) { console.error('Unable to load profile.', error); }
            };
            const showAuthError = (payload, fallback) => {
                const validationMessage = Object.values(payload?.errors ?? {}).flat()[0];
                authError.textContent = validationMessage || payload?.message || fallback;
                authError.classList.remove('hidden');
            };
            const authenticate = async (endpoint, body, form) => {
                authError.classList.add('hidden');
                const submit = form.querySelector('[type="submit"]');
                submit.disabled = true;
                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
                        credentials: 'same-origin',
                        body: JSON.stringify(body),
                    });
                    const payload = await response.json();
                    if (!response.ok || !payload.access_token) {
                        showAuthError(payload, 'Unable to authenticate. Please try again.');
                        return;
                    }
                    localStorage.setItem('auth_token', payload.access_token);
                    window.location.reload();
                } catch (error) {
                    showAuthError(null, 'Unable to reach the server. Please try again.');
                } finally {
                    submit.disabled = false;
                }
            };
            authModeButtons.forEach((button) => button.addEventListener('click', () => {
                const isLogin = button.dataset.authMode === 'login';
                loginForm.classList.toggle('hidden', !isLogin);
                registerForm.classList.toggle('hidden', isLogin);
                authError.classList.add('hidden');
                authModeButtons.forEach((tab) => {
                    const active = tab === button;
                    tab.classList.toggle('bg-cyan-300', active);
                    tab.classList.toggle('text-slate-950', active);
                    tab.classList.toggle('text-slate-400', !active);
                });
            }));
            loginForm.addEventListener('submit', (event) => {
                event.preventDefault();
                authenticate('/api/login', {
                    email: document.getElementById('gate-login-email').value.trim(),
                    password: document.getElementById('gate-login-password').value,
                }, loginForm);
            });
            registerForm.addEventListener('submit', (event) => {
                event.preventDefault();
                authenticate('/api/register', {
                    role: document.getElementById('register-role').value,
                    username: document.getElementById('register-username').value.trim(),
                    email: document.getElementById('register-email').value.trim(),
                    password: document.getElementById('register-password').value,
                    location: document.getElementById('register-location').value.trim(),
                }, registerForm);
            });
            logoutButton.addEventListener('click', async () => {
                logoutButton.disabled = true;
                try {
                    await fetch('/api/logout', { method: 'POST', headers: headers(), credentials: 'same-origin' });
                } finally {
                    localStorage.removeItem('auth_token');
                    window.location.reload();
                }
            });
            editProfileButton.addEventListener('click', () => {
                document.getElementById('profile-username-input').value = currentUser?.username ?? '';
                document.getElementById('profile-location-input').value = currentUser?.location ?? '';
                editProfileError.classList.add('hidden');
                editProfileModal.classList.remove('hidden');
                editProfileModal.classList.add('flex');
            });
            closeProfileModal.addEventListener('click', () => {
                editProfileModal.classList.add('hidden');
                editProfileModal.classList.remove('flex');
            });
            editProfileForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                editProfileError.classList.add('hidden');
                const submit = editProfileForm.querySelector('[type="submit"]');
                submit.disabled = true;
                try {
                    const response = await fetch('/api/profile/update', {
                        method: 'POST',
                        headers: headers(),
                        credentials: 'same-origin',
                        body: new FormData(editProfileForm),
                    });
                    const payload = await response.json();
                    if (!response.ok) {
                        const validationMessage = Object.values(payload.errors ?? {}).flat()[0];
                        throw new Error(validationMessage || payload.message || 'Unable to save your profile.');
                    }
                    updateProfileSummary(payload.data ?? payload);
                    editProfileModal.classList.add('hidden');
                    editProfileModal.classList.remove('flex');
                } catch (error) {
                    editProfileError.textContent = error.message || 'Unable to save your profile.';
                    editProfileError.classList.remove('hidden');
                } finally {
                    submit.disabled = false;
                }
            });
            chatInput.addEventListener('keydown', (event) => { if (event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); sendButton.click(); } });
            if (token) {
                welcomeGate.classList.add('hidden');
                appShell.classList.remove('hidden');
                loadProfile();
                fetchDiscovery('/api/discover', true);
            } else {
                welcomeGate.classList.remove('hidden');
                appShell.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
