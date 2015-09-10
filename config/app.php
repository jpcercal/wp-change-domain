<?php

use Cekurte\Wordpress\ChangeDomain\Provider\ApiControllerProvider;
use Cekurte\Wordpress\ChangeDomain\Provider\DefaultControllerProvider;
use JDesrosiers\Silex\Provider\CorsServiceProvider;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();

$app['debug'] = Helpers::getEnv('APP_DEBUG');

Request::enableHttpMethodParameterOverride();

$app->register(new SessionServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new CorsServiceProvider(),     require CONFIG_PATH . DIRECTORY_SEPARATOR . 'cors.php');
$app->register(new MonologServiceProvider(),  require CONFIG_PATH . DIRECTORY_SEPARATOR . 'monolog.php');
$app->register(new TwigServiceProvider(),     require CONFIG_PATH . DIRECTORY_SEPARATOR . 'twig.php');

require CONFIG_PATH . DIRECTORY_SEPARATOR . 'error.php';

$app->mount('/',    new DefaultControllerProvider());
$app->mount('/api', new ApiControllerProvider());

$app->after($app["cors"]);

return $app;
