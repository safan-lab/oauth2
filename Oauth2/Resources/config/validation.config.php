<?php

return [
    'auth' => [
        'method'      => 'POST',
        'contentType' => ['json', 'application/json', 'form'],
        'required'    => [
            'login' => [
                'token'
            ],
            'authorization_code' => [
                'response_type',
                'client_id',
                'redirect_uri',
                'scope',
                'state'
            ],
            'client_credentials' => [
                'grant_type',
                'client_id',
                'client_secret',
                'scope'
            ],
            'implicit' => [
                'response_type',
                'client_id',
                'redirect_uri',
                'scope',
                'state'
            ],
            'password' => [
                'grant_type',
                'client_id',
                'client_secret',
                'scope',
                'username',
                'password'
            ],
            'refresh_token' => [
                'grant_type',
                'refresh_token',
                'client_id',
                'client_secret',
                'scope',
            ]
        ],
        'grant_types' => [
            'authorization_code' => \Oauth2\GrantTypes\AuthCode::class,
            'client_credentials' => \Oauth2\GrantTypes\ClientCredential::class,
            'implicit'           => \Oauth2\GrantTypes\Implicit::class,
            'password'           => \Oauth2\GrantTypes\Password::class,
            'refresh_token'      => \Oauth2\GrantTypes\RefreshToken::class,
            'login'              => \Oauth2\GrantTypes\Login::class,
        ]
    ]
];