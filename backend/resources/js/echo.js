import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Config is injected by AdminPanelProvider at runtime (see QAC_ECHO_CONFIG),
// so one compiled asset works for both Reverb (local/VPS) and Pusher (shared hosting).
const config = window.QAC_ECHO_CONFIG || {};

if (config.driver === 'pusher') {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: config.key,
        cluster: config.cluster || 'mt1',
        forceTLS: true,
        enabledTransports: ['ws', 'wss'],
    });
} else {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: config.key,
        wsHost: config.host || 'localhost',
        wsPort: Number(config.port) || 8080,
        wssPort: Number(config.port) || 8080,
        forceTLS: (config.scheme || 'http') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
}
