// Kidz Tech Portal Service Worker
const CACHE_NAME = 'kidz-tech-portal-cache-v4';
const urlsToCache = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/images/favicon.png',
    '/images/logo_light.png',
    '/images/logo_dark.png',
    '/manifest.json'
];

// Install event - cache static assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache).catch(err => {
                    console.log('Cache addAll failed:', err);
                });
            })
    );
    self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch event - network first, fallback to cache
self.addEventListener('fetch', event => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    // Skip cross-origin requests
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Clone the response
                const responseToCache = response.clone();

                // Cache successful responses
                if (response.status === 200) {
                    caches.open(CACHE_NAME)
                        .then(cache => {
                            cache.put(event.request, responseToCache);
                        });
                }

                return response;
            })
            .catch(() => {
                // Fallback to cache
                return caches.match(event.request);
            })
    );
});

// Push event - handle incoming push notifications
self.addEventListener('push', event => {
    console.log('Push event received:', event);

    let notificationData = {
        title: 'Kidz Tech Portal',
        body: 'You have a new notification',
        icon: '/images/logo_light.png',
        badge: '/images/favicon.png',
        data: {
            url: '/dashboard'
        }
    };

    // Parse the push event data
    if (event.data) {
        try {
            const payload = event.data.json();
            console.log('Push payload:', payload);

            // FCM sends data in notification object
            if (payload.notification) {
                notificationData.title = payload.notification.title || notificationData.title;
                notificationData.body = payload.notification.body || notificationData.body;

                if (payload.notification.icon) {
                    notificationData.icon = payload.notification.icon;
                }
                if (payload.notification.badge) {
                    notificationData.badge = payload.notification.badge;
                }
            }

            // Additional data for click handling
            if (payload.data) {
                notificationData.data = {
                    ...notificationData.data,
                    ...payload.data
                };
            }

            // Handle FCM webpush specific data
            if (payload.fcmOptions && payload.fcmOptions.link) {
                notificationData.data.url = payload.fcmOptions.link;
            }

        } catch (e) {
            console.error('Error parsing push data:', e);
            // Use default notification data
        }
    }

    const options = {
        body: notificationData.body,
        icon: notificationData.icon,
        badge: notificationData.badge,
        data: notificationData.data,
        vibrate: [200, 100, 200],
        tag: 'kidz-tech-notification',
        requireInteraction: false,
        actions: [
            {
                action: 'open',
                title: 'View',
                icon: '/images/favicon.png'
            },
            {
                action: 'close',
                title: 'Dismiss',
                icon: '/images/favicon.png'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(notificationData.title, options)
            .then(() => {
                console.log('Notification displayed successfully');
            })
            .catch(err => {
                console.error('Error showing notification:', err);
            })
    );
});

// Notification click event - handle when user clicks on notification
self.addEventListener('notificationclick', event => {
    console.log('Notification clicked:', event);

    event.notification.close();

    const urlToOpen = event.notification.data?.url || '/dashboard';

    // Handle action buttons
    if (event.action === 'close') {
        // Just close the notification (already done above)
        return;
    }

    // Open or focus the app window
    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        })
        .then(windowClients => {
            // Check if there's already a window open with the target URL
            for (let client of windowClients) {
                const clientUrl = new URL(client.url);
                const targetUrl = new URL(urlToOpen, self.location.origin);

                // If we find a window with the same origin, focus it and navigate
                if (clientUrl.origin === targetUrl.origin) {
                    if (client.url !== targetUrl.href) {
                        // Navigate to the target URL if different
                        client.navigate(targetUrl.href);
                    }
                    return client.focus();
                }
            }

            // If no window is open, open a new one
            if (clients.openWindow) {
                const fullUrl = urlToOpen.startsWith('http')
                    ? urlToOpen
                    : new URL(urlToOpen, self.location.origin).href;
                return clients.openWindow(fullUrl);
            }
        })
        .catch(err => {
            console.error('Error handling notification click:', err);
        })
    );
});

// Notification close event - track when notifications are dismissed
self.addEventListener('notificationclose', event => {
    console.log('Notification closed:', event);

    // Optional: Send analytics or track dismissals
    // This can be useful for understanding user engagement with notifications
    event.waitUntil(
        Promise.resolve().then(() => {
            // You could send analytics here if needed
            console.log('Notification dismissed by user');
        })
    );
});
