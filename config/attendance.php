<?php

return [
    // Org/user-facing timezone for computing work_date and UI display
    'org_timezone' => env('ATTENDANCE_TZ', 'Asia/Kolkata'),

    // Business rules
    'single_clock_in_out_per_day' => true,
    'geofence' => [
        'enabled' => false,
        'lat' => null,     // e.g., 28.613939
        'lng' => null,     // e.g., 77.209023
        'radius_m' => 150, // meters
    ],

    // Nonce / liveness
    'nonce_ttl_seconds'   => 60,
    'capture_drift_seconds' => 30, // how recent client-captured image must be

    // Image constraints
    'image' => [
        'max_mb' => 3,
        'mimes'  => ['jpeg','jpg','png','webp'],
        'min_width'  => 640,
        'min_height' => 480,
        'store_dir'  => 'attendance', // under storage/app/public/{store_dir}
    ],

    // Retention policy (purge job later)
    'retention_days' => 180,
];
