<?php

return [
    'twig.path'       => APP_PATH,
    'twig.class_path' => VENDOR_PATH . DIRECTORY_SEPARATOR . 'twig' . DIRECTORY_SEPARATOR . 'twig' . DIRECTORY_SEPARATOR . 'lib',
    'twig.options'    => [
        'cache'       => STORAGE_PATH_CACHE . DIRECTORY_SEPARATOR . 'twig.cache',
        'debug'       => $app['debug'],
    ],
];
