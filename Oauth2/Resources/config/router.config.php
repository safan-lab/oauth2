<?php

return [
    '/^\/(oauth2)\/(auth|login)$/i' => [
        'type'       => 'RegExp',
        'module'     => 'Oauth2',
        'controller' => 'oauth',
        'action'     => 'auth',
        'matches' => ['', '', 'action']
    ]
];