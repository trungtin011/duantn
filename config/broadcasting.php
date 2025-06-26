<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
<<<<<<< HEAD
    | any of the broadcasters which have been configured below.
    |
    | Supported: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'null'),
=======
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "reverb", "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_CONNECTION', 'null'),
>>>>>>> bd658a28a89dcbbe87205b492b7250294d4890ad

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
<<<<<<< HEAD
    | Here you may define the connection details for each broadcaster that
    | will be used by your application. Should you choose to use Pusher, Ably
    | or Redis, you may configure them here to connect to your service.
=======
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over WebSockets. Samples of
    | each available type of connection are provided inside this array.
>>>>>>> bd658a28a89dcbbe87205b492b7250294d4890ad
    |
    */

    'connections' => [
<<<<<<< HEAD

=======
>>>>>>> bd658a28a89dcbbe87205b492b7250294d4890ad
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
<<<<<<< HEAD
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'forceTLS' => true,
            ],
            'client_options' => [
                // 'host' => 'api-lo.pusher.com',
                // 'port' => 443,
                // 'scheme' => 'https',
                // 'timeout' => 30,
=======
                'cluster' => 'ap1',
                'useTLS' => true,
            ],
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
>>>>>>> bd658a28a89dcbbe87205b492b7250294d4890ad
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

<<<<<<< HEAD
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

=======
>>>>>>> bd658a28a89dcbbe87205b492b7250294d4890ad
        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

<<<<<<< HEAD
]; 
=======
];
>>>>>>> bd658a28a89dcbbe87205b492b7250294d4890ad
