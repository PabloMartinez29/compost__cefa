<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary' => env('SNAPPY_PDF_BINARY', 'wkhtmltopdf'),
        'timeout' => false,
        'options' => [
            'encoding' => 'UTF-8',
            'enable-javascript' => false,
            'javascript-delay' => 0,
            'no-stop-slow-scripts' => true,
            'no-sandbox' => true,
            'disable-smart-shrinking' => true,
        ],
        'env' => [],
    ],
    'image' => [
        'enabled' => true,
        'binary' => env('SNAPPY_IMAGE_BINARY', 'wkhtmltoimage'),
        'timeout' => false,
        'options' => [
            'encoding' => 'UTF-8',
            'enable-javascript' => false,
            'javascript-delay' => 0,
            'no-stop-slow-scripts' => true,
            'no-sandbox' => true,
            'disable-smart-shrinking' => true,
        ],
        'env' => [],
    ],
];
