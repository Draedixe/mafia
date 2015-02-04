<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\Chat;
use Mafia\PartieBundle\Entity\Parametres;
use Mafia\PartieBundle\Entity\Partie;
use Mafia\PartieBundle\Entity\UserPartie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PartieController extends Controller{

    public function choixTypePartieAction(){
        return $this->render('MafiaPartieBundle:Affichages:choix_type_partie.html.twig' );
    }

    //Un utilisateur rejoint / crée une partie
    public function jouerClassiqueAction(){
        if($this->getUser() != null) {
            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:Partie');
            $em = $this->getDoctrine()->getManager();
            $partiesEnAttentes = $repository->findBy(array("commencee" => false));

            $repositoryUserPartie = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:UserPartie');

            $repositoryParam = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:Parametres');

            foreach($partiesEnAttentes as $key => $p){
                $nbJoueurs = count($repositoryUserPartie->findBy(array("partie" => $p)));
                if($nbJoueurs >= $p->getNombreJoueursMax()){
                    unset($partiesEnAttentes[$key]);
                }
            }
            $nombreParties = count($partiesEnAttentes);
            if ($nombreParties <= 0) {
                $partieChoisie = new Partie();
                $partieChoisie->setNomPartie("Partie de " . $this->getUser()->getUsername());
                $partieChoisie->setDebutPhase(new \DateTime);
                $partieChoisie->setCommencee(false);
                $partieChoisie->setTerminee(false);
                $partieChoisie->setMaireAnnonce(false);



                $allParam = $repositoryParam->findAll();
                if (count($allParam) == 0) {
                    $param = new Parametres();
                    $param->setNomParametres("Par Défaut");
                    $param->setOfficiel(true);
                    $em->persist($param);
                    $em->flush();
                } else {
                    $param = $allParam[0];
                }

                $partieChoisie->setParametres($param);

                $repositoryRoles = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('MafiaRolesBundle:Composition');
                $compo = $repositoryRoles->findOneBy(array("nomCompo" => "Officielle"));
                $partieChoisie->setComposition($compo);

                $chat = new Chat();
                $em->persist($chat);
                $em->flush();

                $partieChoisie->setChat($chat);

                $em->persist($partieChoisie);
                $em->flush();

            } else {
                $partieChoisie = $partiesEnAttentes[0];
            }

            $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:UserPartie');
            $userResponse = $repositoryUser->findBy(array("user" => $this->getUser()));

            $userDansAutrePartie = null;
            foreach($userResponse as $ur){
                if($ur->getPartie()->isCommencee() && $ur->getVivant()){
                    return $this->forward('MafiaPartieBundle:Jeu:debutPartie');
                }
                if(!($ur->getPartie()->isCommencee())){
                    $userDansAutrePartie = $ur;
                }
            }

            //Si on a trouve un UserPartie qui correspond au User
            if ($userDansAutrePartie != null) {
                //On vérifie que le user n'est pas créateur dans une autre partie
                $autrePartie = $repository->findOneBy(array("createur" => $userDansAutrePartie));
                if($autrePartie != null){
                    $autrePartie->setCreateur(Null);
                    $em->persist($autrePartie);
                    $em->flush();
                }
                $em->remove($userDansAutrePartie);
                $em->flush();
            }

            $userPartie = new UserPartie();
            $userPartie->setPartie($partieChoisie);
            $userPartie->setUser($this->getUser());
            $userPartie->setNom($this->getUser()->getUsername());

            $em->persist($userPartie);
            $em->flush();

            //Si c'est le seul utilisateur de la partie, le joueur devient le créateur
            $userResponse = $repositoryUser->findBy(array("partie" => $partieChoisie));
            if (count($userResponse) == 1 || $partieChoisie->getCreateur() == NULL) {
                $partieChoisie->setCreateur($userPartie);
            }

            $em->persist($partieChoisie);
            $em->flush();


            $formBuilder = $this->get('form.factory')->create();
            $formBuilder
                ->add('message', 'text', array('label' => 'Message'));


            //RECUPERATION DES MESSAGES
            $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:UserPartie');

            $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "partie" => $partieChoisie));
            $partie = $user->getPartie();
            $chat = $partie->getChat();

            $repositoryMessage = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:Message');


            $pid = 0;

            $messages = $repositoryMessage->myFind($chat, $pid);

            $data = array();
            $id = 0;
            foreach ($messages as $message) {
                $data[$id] = array("id" => $message->getId(), "pseudo" => $message->getUser()->getUsername(), "message" => $message->getTexte());
                $id++;
            }

            $userList = $repositoryUser->findBy(array("partie" => $partie));
            //LISTE DES UTILISATEURS
            $userData = array();
            $id = 0;
            foreach ($userList as $ul) {
                if ($ul == $partie->getCreateur()) {
                    $userData[$id] = $ul->getUser()->getUsername() . " - Créateur";
                } else {
                    $userData[$id] = $ul->getUser()->getUsername();
                }
                $id++;
            }

            //Liste Parametres
            $parametres = $repositoryParam->findBy(array("officiel" => true));
            $paramTab = array();

            foreach($parametres as $pa){
                $paramTab[$pa->getId()] = $pa->getNomParametres();
            }

            $formBuilderParam = $this->get('form.factory')->create();

            if($user == $partieChoisie->getCreateur()) {
                $formBuilderParam->add('param', 'choice',
                    array('choices' => $paramTab,
                        'data' => $partieChoisie->getParametres()->getId(),
                        'label' => 'Paramètres'
                    ));
            }
            else{
                $formBuilderParam->add('param', 'choice',
                    array('choices' => $paramTab,
                        'data' => $partieChoisie->getParametres()->getId(),
                        'label' => 'Paramètres',
                        'disabled' => true
                    ));
            }


            return $this->render('MafiaPartieBundle:Affichages:jouer_classique.html.twig', array(
                'partie' => $partieChoisie, 'form' => $formBuilder->createView(), 'messages' => $data, 'users' => $userData,
                'compo' => $partieChoisie->getComposition()->getNomCompo(),
                'paramForm' => $formBuilderParam->createView()
            ));
        }
        else{
            return $this->forward('MafiaUserBundle:Default:menu');
        }
    }

    public function lancerPartieAction(){
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        $partie = $user->getPartie();

        $userList = $repositoryUser->findBy(array("partie"=>$partie));


        if($user == $partie->getCreateur()) {

             if(count($userList) == $partie->getNombreJoueursMax()){
                $em = $this->getDoctrine()->getManager();
                $partie->setCommencee(true);
                $em->persist($partie);
                $em->flush();
                return new JsonResponse(array('lancer' => true));
            } else {
                return new JsonResponse(array('lancer' => false));
            }
        }
        else{
            return new JsonResponse(array('lancer' => false));
        }
    }

    public function changerParametresAction(){
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');
        $em = $this->getDoctrine()->getManager();
        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        $partie = $user->getPartie();
        //On vérifie que le joueur est le créateur
        if($user == $partie->getCreateur()) {
            $request = $this->container->get('request');
            $paramId = $request->get('param');
            //On vérifie s'il a bien envoyé le parametre
            if($paramId != null) {
                $repositoryParametres = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('MafiaPartieBundle:Parametres');
                $param = $repositoryParametres->find($paramId);
                if($param != null) {
                    $partie->setParametres($param);
                    $em->persist($partie);
                    $em->flush();
                }
            }
        }
        return new JsonResponse();
    }
}