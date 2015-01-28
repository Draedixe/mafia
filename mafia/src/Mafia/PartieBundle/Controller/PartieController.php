<?php

namespace Mafia\PartieBundle\Controller;


use Mafia\PartieBundle\Entity\Chat;
use Mafia\PartieBundle\Entity\Parametres;
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
            $partieChoisie->setNomPartie("Partie de " . $this->getUser()->getUsername());
            $partieChoisie->setTempsEnCours(new \DateTime);
            $partieChoisie->setCommencee(false);
            $partieChoisie->setTerminee(false);
            $partieChoisie->setMaireAnnonce(false);

            $repositoryParam = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:Parametres');

           // $param = $repositoryParam->findOneBy(array("nomParametres"=>"Officiel"));
           // $partieChoisie->setParametres($param);

            $allParam = $repositoryParam->findAll();
            if(count($allParam) == 0){
                $param = new Parametres();
                $param->setNomParametres("Par Défaut");
                $em->persist($param);
                $em->flush();
            }
            else{
                $param = $allParam[0];
            }

            $partieChoisie->setParametres($param);

            $repositoryRoles = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaRolesBundle:Composition');
            $compo = $repositoryRoles->findOneBy(array("nomCompo"=>"Officiel"));
            $partieChoisie->setComposition($compo);

            $chat = new Chat();
            $em->persist($chat);
            $em->flush();

            $partieChoisie->setChat($chat);

            $em->persist($partieChoisie);
            $em->flush();

        }
        else{
            $partieChoisie = $partiesEnAttentes[0];
        }

        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');
        $userResponse = $repositoryUser->findOneBy(array("user" => $this->getUser()));
        if(count($userResponse) != null){
            $em -> remove($userResponse);
            $em -> flush();
        }
        $userPartie = new UserPartie();
        $userPartie->setPartie($partieChoisie);
        $userPartie->setUser($this->getUser());
        $userPartie->setNom($this->getUser()->getUsername());

        $em->persist($userPartie);
        $em->flush();

        $em->persist($partieChoisie);
        $em->flush();


        $formBuilder = $this->get('form.factory')->create();
        $formBuilder
            ->add('message', 'text', array('label' => 'Message'));



        return $this->render('MafiaPartieBundle:Affichages:jouer_classique.html.twig' , array(
            'partie' => $partieChoisie, 'form' => $formBuilder->createView()
        ));
    }
}