<?php

return [
    'tenant_id'     => env('POWERBI_TENANT_ID'),
    'client_id'     => env('POWERBI_CLIENT_ID'),
    'client_secret' => env('POWERBI_CLIENT_SECRET'),
    'workspace_id'  => env('POWERBI_WORKSPACE_ID'),
    'dataset_id'    => env('POWERBI_DATASET_ID'),
    'report_id'     => env('POWERBI_REPORT_ID'),
    'embed_url'     => env('POWERBI_EMBED_URL'),

    'llm' => [
        'base_url' => rtrim(env('NINEROUTER_BASE_URL', 'https://9router.fithlail.my.id/v1'), '/'),
        'api_key'  => env('NINEROUTER_API_KEY', 'sk-d3a40b875db690e2-2zrwlr-1957b2d7'),
        'model'    => env('NINEROUTER_MODEL', 'pbi-analyst'),
        'timeout'  => (int) env('NINEROUTER_TIMEOUT', 60),
    ],
];
