<?php

namespace Mafia\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function menuAction()
    {
        return $this->render('MafiaUserBundle:Default:index.html.twig');
    }
}
