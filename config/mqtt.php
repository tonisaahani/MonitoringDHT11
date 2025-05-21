<?php

return [
    'default_connection' => 'default',

    'connections' => [
        'default' => [
            'host' => env('MQTT_HOST', 'test.mosquitto.org'),
            'port' => env('MQTT_PORT', 1883),
            'username' => env('MQTT_USERNAME', null),
            'password' => env('MQTT_PASSWORD', null),
            'use_tls' => env('MQTT_TLS', false),
            'timeout' => 60,
            'keep_alive' => 60,
            'client_id' => null,
            'properties' => [],
        ],
    ],
];
