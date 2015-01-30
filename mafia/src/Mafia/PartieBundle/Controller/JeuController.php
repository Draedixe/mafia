<?php

namespace Mafia\PartieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JeuController extends Controller{

    public function debutPartieAction()
    {
        return $this->render('MafiaPartieBundle:Affichages:jeu.html.twig');
    }

}