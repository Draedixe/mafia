<?php

namespace Mafia\PartieBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartieController extends Controller{
    public function choixTypePartieAction(){
        return $this->render('MafiaPartieBundle:Affichages:choix_type_partie.html.twig' );
    }

    public function jouerClassiqueAction(){
        return $this->render('MafiaPartieBundle:Affichages:jouer_classique.html.twig' );
    }
}