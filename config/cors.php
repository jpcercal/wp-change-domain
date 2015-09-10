<?php

return [
    'cors.allowOrigin'      => Helpers::getEnv('CORS_ALLOW_ORIGIN'),
    'cors.allowMethods'     => Helpers::getEnv('CORS_ALLOW_METHODS'),
    'cors.maxAge'           => Helpers::getEnv('CORS_MAX_AGE'),
    'cors.allowCredentials' => Helpers::getEnv('CORS_ALLOW_CREDENTIALS'),
    'cors.exposeHeaders'    => Helpers::getEnv('CORS_EXPOSE_HEADERS'),
];
