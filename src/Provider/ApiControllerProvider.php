<?php

namespace Cekurte\Wordpress\ChangeDomain\Provider;

use Cekurte\Wordpress\ChangeDomain\Controller\ApiController;
use Silex\Application;
use Silex\ControllerProviderInterface;

class ApiControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['api.controller'] = $app->share(function() use ($app) {
            return new ApiController($app);
        });

        $controllers = $app['controllers_factory'];

        $controllers->post('/change-domain', "api.controller:indexAction")->bind('change_domain');

        return $controllers;
    }
}
