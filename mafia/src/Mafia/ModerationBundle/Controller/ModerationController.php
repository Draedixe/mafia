<?php

namespace Mafia\ModerationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ModerationController extends Controller
{

    public function tableauModerationAction(){

        return $this->render('MafiaModerationBundle:Affichages:tableau_moderation.html.twig', array());
    }
}