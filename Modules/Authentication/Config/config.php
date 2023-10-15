<?php

return [
    'name' => 'Authentication',
    'token' => [
        "secret" => env("PASSPORT_SECRET", "DHSHDJSHJDSHcxncbxbcxnbcxn474874843784"),
        "remember_token_expires_in" => env("REMEMBER_TOKEN_EXPIRATION", 2)
    ]
];
