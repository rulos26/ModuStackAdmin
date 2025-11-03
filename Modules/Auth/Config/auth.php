<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Token Expiration
    |--------------------------------------------------------------------------
    |
    | Tiempo de expiración de los tokens en minutos.
    | Por defecto: 1 día (1440 minutos)
    |
    */
    'token_expiration' => env('AUTH_TOKEN_EXPIRATION', 1440),

    /*
    |--------------------------------------------------------------------------
    | Enable Registration
    |--------------------------------------------------------------------------
    |
    | Permite o deshabilita el registro de nuevos usuarios.
    |
    */
    'enable_registration' => env('AUTH_ENABLE_REGISTRATION', true),

    /*
    |--------------------------------------------------------------------------
    | Token Name
    |--------------------------------------------------------------------------
    |
    | Nombre del token que se generará para la autenticación.
    |
    */
    'token_name' => env('AUTH_TOKEN_NAME', 'auth_token'),
];

