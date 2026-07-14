const CACHE_NAME = 'sugarconn-cache-v1';
const CORE_ASSETS = [
    '/',
    '/offline',
    '/build/assets/app.css', // This will need to match the actual built asset in production, but we can't easily dynamic it in a basic service worker without a build step. We'll cache what we can.
    '/build/assets/app.js',
    '/manifest.json'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                // We use addAll but wrap it in a try-catch equivalent by ignoring failures for individual files if needed,
                // but for a basic implementation, this is fine.
                return cache.addAll(CORE_ASSETS).catch(err => console.warn('Failed to cache some assets', err));
            })
    );
});

self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .catch(() => {
                return caches.match(event.request)
                    .then(response => {
                        if (response) {
                            return response;
                        }
                        // If both the network and the cache fail, show the offline page
                        if (event.request.mode === 'navigate') {
                            return caches.match('/offline');
                        }
                        return new Response('Network error happened', {
                            status: 408,
                            headers: { 'Content-Type': 'text/plain' },
                        });
                    });
            })
    );
});
