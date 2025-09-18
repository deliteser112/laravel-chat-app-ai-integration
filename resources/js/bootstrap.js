window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

// Dual-mode Echo configuration: Soketi (local) or Pusher Cloud
const echoConfig = {
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
};

// Check if we have a local Soketi host configured
if (process.env.MIX_PUSHER_HOST) {
    // Soketi mode - use local WebSocket server
    echoConfig.wsHost = process.env.MIX_PUSHER_HOST;
    echoConfig.wsPort = process.env.MIX_PUSHER_PORT;
    echoConfig.forceTLS = false;
    echoConfig.encrypted = false;
} else {
    // Pusher Cloud mode - use cluster and TLS
    echoConfig.cluster = process.env.MIX_PUSHER_APP_CLUSTER;
    echoConfig.forceTLS = true;
    echoConfig.encrypted = true;
}

window.Echo = new Echo(echoConfig);
