<?php

namespace Mafia\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MafiaChatBundle:Default:index.html.twig', array('name' => $name));
    }
}
