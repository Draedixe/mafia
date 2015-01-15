<?php

namespace Mafia\RolesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MafiaRolesBundle:Default:index.html.twig', array('name' => $name));
    }
}
