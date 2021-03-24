<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAA0SCdR04:APA91bEiasPFg_vmgxJZ9ZMC7u9HP7fDdjWl4VIE0ebRsQj-wYwggGarmWsM2tHYq6OApxRVF_1etavmbdIKwdfSQ8IhEJl2G8t4rxoFnF6p9Vf9ZSiNuC66SWDbLXOZNnD1UHUaMLZN'),
        'sender_id' => env('FCM_SENDER_ID', '898195343182'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0, // in second
    ],
];
