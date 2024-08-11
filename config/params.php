<?php

return [

    'qr_code_settings' => [
        'endpoint' => env('QR_CODE_URL', 'https://api.qrserver.com/v1/create-qr-code/'),
        'size' => env('QR_CODE_SIZE', '300x300')
    ]

];
