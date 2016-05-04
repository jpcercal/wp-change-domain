<?php

namespace Cekurte\Wordpress\ChangeDomain\Controller;

use Silex\Application;

/**
 * The base WebController
 */
abstract class WebController
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return Silex\Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Generate a URL
     *
     * @param  string $route
     * @param  array  $params
     * @return string
     */
    public function generateUrl($route, array $params = [])
    {
        $app = $this->getApp();

        if (!isset($app['url_generator'])) {
            throw new \RuntimeException('The UrlGeneratorServiceProvider is not registered in this application');
        }

        return $app['url_generator']->generate($route, $params);
    }

    /**
     * Render a view using the Twig Template Engine
     *
     * @param  string $view
     * @param  array $params
     *
     * @return string
     */
    public function render($view, array $params = [])
    {
        $app = $this->getApp();

        if (!isset($app['twig'])) {
            throw new \RuntimeException('The TwigServiceProvider is not registered in this application');
        }

        return $app['twig']->render(sprintf('Resources/views/%s', $view), $params);
    }
}
