import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: import.meta.env.VITE_BROADCASTER,
    cluster: import.meta.env.VITE_BROADCAST_CLUSTER,
    key: import.meta.env.VITE_BROADCAST_KEY,
    wsHost: import.meta.env.VITE_BROADCAST_HOST,
    wsPort: import.meta.env.VITE_BROADCAST_PORT ?? 80,
    wssPort: import.meta.env.VITE_BROADCAST_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_BROADCAST_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
