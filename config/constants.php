<?php
$value_active = 'ACTIVE';
$value_inactive = 'INACTIVE';
$value_available = 'AVAILABLE';
$value_used = 'USED';
return [
    'response_codes' => [
        'success' => '200',
        'validation_error' => '422',
        'internal_server_error' => '500',
    ],
    'response_status' => [
        'success' => 'success',
        'validation_error' => 'fail',
        'internal_server_error' => 'error',
        'common_error' => 'error',
    ],
    'radar_constants' => [
        'strava_integration_id' => 1,
        'activity-type' => 'ACTIVITY',
        'welcome_type' => 'WELCOME',
        'integration_type' => 'INTEGRATION_COMPLETE',
        'challenge_complete' => 'CHALLENGE_COMPLETE',
    ],
    'event_types' => [
        'CHALLENGE' => 'CHALLENGE'
    ],
    'event' => [
        'in_progress' => 'IN_PROGRESS',
        'complete' => 'COMPLETE',
        'incomplete' => 'INCOMPLETE',
        'status_active' => 'ACTIVE',
        'status_incomplete' => 'INCOMPLETE',
        'status_finished' => 'FINALIZED',
    ],
    'sendgrid_constants' => [
        'welcome_template_id' => 'd-5f7e1908b0c24f5da998a32c2f3f565b',
        'challenge_inscription_id' => 'd-2bdb1c03a915434e9d54e0b6e0c34f7d',
        'challenge_complete_id' => 'd-15bf032b220d413e83e2406f9424c059',
        'reward_redemption_id' => 'd-bb567e4448b94eb69cc89e9232fdbeb4',
    ],
    'active_status' => $value_active,
    'inactive_status' => $value_inactive,
    'available_status' => $value_available,
    'used_status' => $value_used,
    'status_model' => [
        $value_active => 'Activo',
        $value_inactive => 'Inactivo',
    ],
    'wallet_log_type' => [
        'customer_redemption' => 'CUSTOMER_REDEMPTION',
        'customer_activity' => 'CUSTOMER_ACTIVITY',
    ],
    'roles' => [
        'role_administrator' => 'Administrador',
        'role_operator' => 'Proveedor',
        'role_client' => 'Cliente',
    ],
    'tax' => (float)env('TAX_VALUE', 0),
    'logicFileSystem' => env('LOGIC_FILESYSTEM','s3'),
    
    'oauthPassport' => [
        'tokenAccessClient' => env('CLIENT_ACCESS_TOKEN')
    ],
    'nameImageParameter' => 'profile',
    'nameImageParameterProduct' => 'Image',
    'firebasekey' => env('FIREBASE_CREDENTIALS'),
    'urlDirection' => env('APP_URL')

];
