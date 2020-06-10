const paginaOffline = "../paginaOffline.html";

self.addEventListener('install', function(e) {
 e.waitUntil(
   caches.open('cachePagineAppunti').then(function(cache) {
     return cache.addAll([
       '../login.html',
       '../index.php?',
       paginaOffline
     ]);
   })
 );
});

self.addEventListener('fetch', (event) => {
    // Rispondiamo solo a una richiesta per una pagina web
    if (event.request.mode === 'navigate') {
        event.respondWith((async () => {
            try {
                // Prova a usare la preloadResponse
                const preloadResponse = await event.preloadResponse;
                if (preloadResponse) {
                    return preloadResponse;
                }

                const networkResponse = await fetch(event.request);
                return networkResponse;
            } catch (error) {
                // Qualcosa è andato storto; probabilmente sei offline!
                console.log('Restituisco la pagina offline', error);

                const cache = await caches.open('cachePagineAppunti');
                const cachedResponse = await cache.match(paginaOffline);
                return cachedResponse;
            }
        })());
    }
});

self.addEventListener('push', function(event) {
  const title = "Nuovo appunto inserito!";
  const options = {
    body: event.data.text(),
    icon: 'res/logo.jpg',
    badge: 'res/logo.jpg'
  };

  event.waitUntil(self.registration.showNotification(title, options));
});
