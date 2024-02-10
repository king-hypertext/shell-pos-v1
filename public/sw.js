// Declare the cache name and the URLs to cache
const cacheName = 'my-app-cache-v1';
const urlsToCache = [
    '/',
    '/login',
    '/dashboard',
    '/assets/plugins/bootstrap/css/bootstrap.css',
    '/assets/plugins/bootstrap/js/bootstrap.bundle.js',
    '/assets/plugins/mdb/mdb.min.css',
    '/assets/plugins/mdb/js/mdb.min.js',
    '/assets/plugins/jquery-ui-1.13.2/external/jquery/jquery.js',
    '/assets/index.css',
    '/assets/index.js',
    '/logo.png'
];

// Install the service worker and cache the resources
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(cacheName)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

// Fetch the resources from the cache or the network
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(cachedResponse => {
                // Return the cached response if it exists
                if (cachedResponse) {
                    return cachedResponse;
                }
                // Otherwise, fetch the resource from the network
                return fetch(event.request)
                    .then(networkResponse => {
                        // Cache the network response for future use
                        return caches.open(cacheName)
                            .then(cache => {
                                cache.put(event.request, networkResponse.clone());
                                return networkResponse;
                            });
                    });
            })
    );
});

// Activate the service worker and delete any old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                // Filter out the cache names that are not needed anymore
                return cacheNames.filter(cacheName => cacheName !== cacheName);
            })
            .then(cachesToDelete => {
                // Delete the old caches
                return Promise.all(
                    cachesToDelete.map(cacheToDelete => {
                        return caches.delete(cacheToDelete);
                    })
                );
            })
            .then(() => self.clients.claim())
    );
});
