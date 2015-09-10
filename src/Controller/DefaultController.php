<?php

namespace Cekurte\Wordpress\ChangeDomain\Controller;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends WebController
{
    public function indexAction(Request $request)
    {
        return $this->render('Default/index.twig.html', [
            'title' => 'Trocar Domínio Wordpress - Cekurte Sistemas',
            'debug' => $this->getApp()['debug'],
        ]);
    }
}
