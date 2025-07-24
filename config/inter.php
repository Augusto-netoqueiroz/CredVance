<?php

return [
    'use_sandbox' => env('INTER_USE_SANDBOX', false),

    'client_id'     => env('INTER_CLIENT_ID'),
    'client_secret' => env('INTER_CLIENT_SECRET'),

    'api' => [
        'token_url_prod'       => env('INTER_TOKEN_URL_PROD'),
        'base_url_prod'        => env('INTER_BASE_URL_PROD_RESOURCES'),
        'cobranca_path_prod'   => env('INTER_COBRANCA_PATH_PROD', '/cobranca/v3/cobrancas'),

        'token_url_sandbox'    => env('INTER_TOKEN_URL_SANDBOX'),
        'base_url_sandbox'     => env('INTER_BASE_URL_SANDBOX'),
        'cobranca_path_sandbox'=> env('INTER_COBRANCA_PATH_SANDBOX', '/cobranca/v3/cobrancas'),
    ],

    'token_scope'     => env('INTER_TOKEN_SCOPE', ''),
    'conta_corrente'  => env('INTER_CONTA_CORRENTE', ''),
    'cert_path'       => env('INTER_CERT_PATH'),
    'cert_key_path'   => env('INTER_CERT_KEY_PATH'),
    'ca_path'         => env('INTER_CA_PATH'),
];
