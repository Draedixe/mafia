<?php

namespace Mafia\PartieBundle\Controller;


use Mafia\PartieBundle\Entity\Partie;
use Mafia\PartieBundle\Entity\UserPartie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartieController extends Controller{

    public function choixTypePartieAction(){
        return $this->render('MafiaPartieBundle:Affichages:choix_type_partie.html.twig' );
    }

    public function jouerClassiqueAction(){

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Partie');
        $em = $this->getDoctrine()->getManager();
        $partiesEnAttentes = $repository->findBy(array("commencee"=>false));
        $nombreParties = count($partiesEnAttentes);
        if($nombreParties <= 0){
            $partieChoisie = new Partie();
            $partieChoisie->setNomPartie("Partie de " . $this->getUser()->getNickname());
            $em->persist($partieChoisie);
            $em->flush();

        }
        else{
            $choix = rand(0,count($partiesEnAttentes));
            $partieChoisie = $partiesEnAttentes[$choix];
        }
        $userPartie = new UserPartie();
        $userPartie->setPartie($partieChoisie);

        $em->persist($partieChoisie);
        $em->flush();
        return $this->render('MafiaPartieBundle:Affichages:jouer_classique.html.twig' , array(
            'partie' => $partieChoisie
        ));
    }
}