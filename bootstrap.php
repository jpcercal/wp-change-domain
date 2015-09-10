<?php

define('ROOT_PATH', realpath(__DIR__));

define('PUBLIC_PATH',  realpath(ROOT_PATH . DIRECTORY_SEPARATOR . 'public'));
define('APP_PATH',     realpath(ROOT_PATH . DIRECTORY_SEPARATOR . 'src'));
define('VENDOR_PATH',  realpath(ROOT_PATH . DIRECTORY_SEPARATOR . 'vendor'));
define('CONFIG_PATH',  realpath(ROOT_PATH . DIRECTORY_SEPARATOR . 'config'));
define('STORAGE_PATH', realpath(ROOT_PATH . DIRECTORY_SEPARATOR . 'storage'));

define('STORAGE_PATH_CACHE', realpath(STORAGE_PATH . DIRECTORY_SEPARATOR . 'cache'));
define('STORAGE_PATH_LOG',   realpath(STORAGE_PATH . DIRECTORY_SEPARATOR . 'logs'));

require VENDOR_PATH . DIRECTORY_SEPARATOR . 'autoload.php';
require CONFIG_PATH . DIRECTORY_SEPARATOR . 'dotenv.php';
require CONFIG_PATH . DIRECTORY_SEPARATOR . 'helpers.php';
