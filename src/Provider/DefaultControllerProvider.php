<?php

namespace Cekurte\Wordpress\ChangeDomain\Provider;

use Cekurte\Wordpress\ChangeDomain\Controller\DefaultController;
use Silex\Application;
use Silex\ControllerProviderInterface;

class DefaultControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['default.controller'] = $app->share(function() use ($app) {
            return new DefaultController($app);
        });

        $controllers = $app['controllers_factory'];

        $controllers->get('/', "default.controller:indexAction")->bind('home');

        return $controllers;
    }
}
