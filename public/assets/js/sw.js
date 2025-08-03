// Core assets
let coreAssets = ['/assets/images/logo.png', '/assets/img/apple-touch-icon.png' , '/assets/img/favicon-32x32.png' , '/assets/img/favicon-16x16.png' , '/assets/fonts/ionicons.woff2?v=4.5.5'];

// On install, cache core assets
self.addEventListener('install', function (event) {

    // Cache core assets
    event.waitUntil(caches.open('app').then(function (cache) {
        return cache.addAll(coreAssets);
    }));

});

// Listen for request events

self.addEventListener('activate', (event) => {
    // Specify allowed cache keys
    // Get all the currently active `Cache` instances.
    event.waitUntil(caches.keys().then((keys) => {
        // Delete all caches that aren't in the allow list:
        return Promise.all(keys.map((key) => {
            return caches.delete(key);
        }));
    }));
});

// Listen for request events
self.addEventListener('fetch', async (event) => {

    // Get the request
    let request = event.request;

    // Bug fix
    // https://stackoverflow.com/a/49719964
    if (event.request.cache === 'only-if-cached' && event.request.mode !== 'same-origin') return;

    // HTML files
    // Network-first


    if (request.headers.get('Accept').includes('text/html')) {
        event.respondWith(
            fetch(request).then(function (response) {
                return response;
            }).catch(function () {
                return Response.redirect('https://Mehentai.nl/', 302);

            })
        );
    }

    // CSS & JavaScript
    // Offline-first

    // Images
    // Offline-first
    if (event.request.destination === 'style' || event.request.destination === 'script' || event.request.destination === 'font'){
        event.respondWith(
            caches.match(request).then(function (response) {
                return response || fetch(request).then(function (response) {

                    // Save a copy of it in cache
                    let copy = response.clone();
                    event.waitUntil(caches.open('app').then(function (cache) {
                        return cache.put(request, copy);
                    }));
                    // Return the response
                    return response;

                });
            })
        );
    }

});

