<script src="/vendor/pusher.min.js"></script>
<script src="/vendor/echo.iife.js"></script>
<script>
    (function () {
        if (typeof Echo === 'undefined' || typeof Pusher === 'undefined') {
            console.error('[Realtime] Echo/Pusher not loaded.');
            return;
        }

        var pusherKey = @json(env('PUSHER_APP_KEY'));
        if (!pusherKey) {
            console.warn('[Realtime] PUSHER_APP_KEY manquant.');
            return;
        }

        window.Pusher = window.Pusher || Pusher;

        var options = {
            broadcaster: 'pusher',
            key: pusherKey,
            cluster: @json(env('PUSHER_APP_CLUSTER')),
            forceTLS: @json(env('PUSHER_SCHEME', 'https') === 'https'),
            namespace: 'App.Events'
        };

        var host = @json(env('PUSHER_HOST'));
        var port = @json(env('PUSHER_PORT'));
        if (host) {
            var parsedPort = parseInt(port, 10);
            options.wsHost = host;
            options.wsPort = Number.isFinite(parsedPort) ? parsedPort : 80;
            options.wssPort = Number.isFinite(parsedPort) ? parsedPort : 443;
            options.enabledTransports = ['ws', 'wss'];
        }

        if (window.Echo) {
            return;
        }

        var EchoConstructor = (typeof Echo === 'function')
            ? Echo
            : (Echo && Echo.default ? Echo.default : null);

        if (!EchoConstructor) {
            console.error('[Realtime] Echo constructor introuvable.');
            return;
        }

        window.Echo = new EchoConstructor(options);
    })();
</script>
