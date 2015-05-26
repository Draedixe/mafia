<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\Chat;
use Mafia\PartieBundle\Entity\Parametres;
use Mafia\PartieBundle\Entity\Partie;
use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Mafia\PartieBundle\Entity\UserPartie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PartieController extends Controller{

    public function choixTypePartieAction(){
        return $this->render('MafiaPartieBundle:Affichages:choix_type_partie.html.twig' );
    }

    //Un utilisateur rejoint / crée une partie
    public function jouerTestAction(){
        return PartieController::jouerPartie("test");
    }

    public function jouerClassiqueAction(){
        return PartieController::jouerPartie("classique");
    }

    //Un utilisateur rejoint / crée une partie
    public function jouerPartie($type){
        if($this->getUser() != null) {
            ////DEBUT ON FAIT LE PLEIN DE REPOSITORY
            $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:UserPartie');
            $userResponse = $repositoryUser->findBy(array("user" => $this->getUser()));

            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:Partie');
            $em = $this->getDoctrine()->getManager();

            $repositoryUserPartie = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:UserPartie');

            $repositoryParam = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:Parametres');
            ////FIN ON FAIT LE PLEIN DE REPOSITORY

            $userDansAutrePartie = null;
            //On cherche si le joueur est déja dans une partie (dans ce cas on ne change rien et on réaffiche la partie)
            foreach($userResponse as $ur){
                //Si le user est dans une partie commencee et qu'il est encore vivant
                if($ur->getPartie()->isCommencee() && $ur->getVivant()){
                    return $this->forward('MafiaPartieBundle:Jeu:debutPartie');
                }
                //Si le user est déjà dans une partie pas commencée
                if(!($ur->getPartie()->isCommencee())){
                    $userDansAutrePartie = $ur;
                }
            }

            //Si on a trouve un UserPartie qui correspond au User
            if ($userDansAutrePartie != null) {
                $partieChoisie = $userDansAutrePartie->getPartie();
            }
            else {

                //On repère les parties existantes (tri en fonction du type)
                $partiesEnAttentes = $repository->findBy(array("commencee" => false, "typePartie" => $type));


                //On enlève les parties qui sont pleines
                foreach ($partiesEnAttentes as $key => $p) {
                    $nbJoueurs = count($repositoryUserPartie->findBy(array("partie" => $p)));
                    if ($nbJoueurs >= $p->getNombreJoueursMax()) {
                        unset($partiesEnAttentes[$key]);
                    }
                }
                //On compte les parties qui sont joignables
                $nombreParties = count($partiesEnAttentes);
                //Si il n'existe pas de partie joignable on la crée
                if ($nombreParties <= 0) {
                    $partieChoisie = new Partie();
                    $partieChoisie->setNomPartie("Partie de " . $this->getUser()->getUsername());
                    $partieChoisie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                    $partieChoisie->setDureePhase(1);
                    $partieChoisie->setTempsJourRestant(1);
                    $partieChoisie->setDebutPhase(new \DateTime());
                    $partieChoisie->setCommencee(false);
                    $partieChoisie->setTerminee(false);
                    $partieChoisie->setMaireAnnonce(false);
                    $partieChoisie->setTypePartie($type);
                    //3 joueurs pour la compo de test
                    if (strcmp($type, "test") == 0) {
                        $partieChoisie->setNombreJoueursMax(3);
                    }


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
                    //Compo spéciale pour les tests
                    $compo = null;
                    if (strcmp($type, "test") == 0) {
                        $compo = $repositoryRoles->findOneBy(array("nomCompo" => "TEST"));

                    } else {
                        $compo = $repositoryRoles->findOneBy(array("nomCompo" => "Officielle"));
                    }

                    $partieChoisie->setComposition($compo);
                    $partieChoisie->setPhaseEnCours(0);
                    //Création du chat
                    $chat = new Chat();
                    $em->persist($chat);
                    $em->flush();

                    $partieChoisie->setChat($chat);

                    $em->persist($partieChoisie);
                    $em->flush();

                } //Il existe au moins une partie joignable donc on la rejoint
                else {
                    $partieChoisie = array_shift($partiesEnAttentes);
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
            }

            //Formulaire pour l'affichage
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

            //LISTE DES PARAMETRES ET DES COMPOSITIONS
            $parametres = $repositoryParam->findBy(array("officiel" => true));
            $paramTab = array();

            $repositoryComposition = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaRolesBundle:Composition');
            $compositions = $repositoryComposition->findBy(array("officielle" => true));
            $compoTab = array();

            foreach($parametres as $pa){
                $paramTab[$pa->getId()] = $pa->getNomParametres();
            }

            foreach($compositions as $co){
                $compoTab[$co->getId()] = $co->getNomCompo();
            }

            $formBuilderParam = $this->get('form.factory')->create();
            $formBuilderCompo = $this->get('form.factory')->create();

            if($user == $partieChoisie->getCreateur()) {
                $formBuilderParam->add('param', 'choice',
                    array('choices' => $paramTab,
                        'data' => $partieChoisie->getParametres()->getId(),
                        'label' => 'Paramètres'
                    ));
                $formBuilderCompo->add('compo', 'choice',
                    array('choices' => $compoTab,
                        'data' => $partieChoisie->getComposition()->getId(),
                        'label' => 'Composition'
                    ));
            }
            else{
                $formBuilderParam->add('param', 'choice',
                    array('choices' => $paramTab,
                        'data' => $partieChoisie->getParametres()->getId(),
                        'label' => 'Paramètres',
                        'disabled' => true
                    ));
                $formBuilderCompo->add('compo', 'choice',
                    array('choices' => $compoTab,
                        'data' => $partieChoisie->getComposition()->getId(),
                        'label' => 'Composition',
                        'disabled' => true
                    ));
            }

            //ROLES
            $rolesData = PartieController::recuperationComposition($user);

            return $this->render('MafiaPartieBundle:Affichages:jouer_classique.html.twig', array(
                'partie' => $partieChoisie, 'form' => $formBuilder->createView(), 'messages' => $data, 'users' => $userData,
                'paramForm' => $formBuilderParam->createView(),
                'compoForm' => $formBuilderCompo->createView(),
                'roles' => $rolesData
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

        if($user != null) {
            if ($user == $partie->getCreateur()) {
                //On vérifie que la partie est bien pleine
                $nbJoueurs = count($userList);
                if ($nbJoueurs == $partie->getNombreJoueursMax()) {
                    $compo = $partie->getComposition();
                    $roles = $compo->getRolesCompo();
                    $cat = $compo->getCategoriesCompo();
                    $nbRoles = 0;
                    foreach($roles as $r){
                       $nbRoles = $nbRoles + $r->getQuantite();
                    }
                    foreach($cat as $r){
                        $nbRoles = $nbRoles + $r->getQuantite();
                    }
                    //On vérifie qu'il y a autant de roles que de joueurs
                    if($nbJoueurs == $nbRoles) {
                        $em = $this->getDoctrine()->getManager();
                        $partie->setCommencee(true);
                        $em->persist($partie);
                        $em->flush();
                        return new JsonResponse(array('lancer' => true));
                    }
                    else{
                        return new JsonResponse(array('lancer' => false, 'compo' => true));
                    }
                }
            }
        }
        return new JsonResponse(array('lancer' => false, 'compo' => false));
    }

    public function changerParametresAction(){
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');
        $em = $this->getDoctrine()->getManager();
        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $partie = $user->getPartie();
            //On vérifie que le joueur est le créateur
            if ($user == $partie->getCreateur()) {
                $request = $this->container->get('request');
                $paramId = $request->get('param');
                //On vérifie s'il a bien envoyé le parametre
                if ($paramId != null) {
                    $repositoryParametres = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MafiaPartieBundle:Parametres');
                    $param = $repositoryParametres->find($paramId);
                    if ($param != null) {
                        if ($param->getOfficiel()) {
                            $partie->setParametres($param);
                            $em->persist($partie);
                            $em->flush();
                        }
                    }
                }
            }
        }
        return new JsonResponse();
    }

    public function changerCompositionAction(){
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');
        $em = $this->getDoctrine()->getManager();
        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $partie = $user->getPartie();
            //On vérifie que le joueur est le créateur
            if ($user == $partie->getCreateur()) {
                $request = $this->container->get('request');
                $compoId = $request->get('compo');
                //On vérifie s'il a bien envoyé le parametre
                if ($compoId != null) {
                    $repositoryComposition = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MafiaRolesBundle:Composition');
                    $compo = $repositoryComposition->find($compoId);
                    if ($compo != null) {
                        if ($compo->getOfficielle()) {
                            $partie->setComposition($compo);
                            $em->persist($partie);
                            $em->flush();
                        }
                    }
                }
            }
        }
        return new JsonResponse();
    }

    public function getCompositionAction(){
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $rolesData = PartieController::recuperationComposition($user);
            return new JsonResponse(array('roles' => $rolesData));
        }
        return new JsonResponse();
    }

    public function recuperationComposition($user){
        $partie = $user->getPartie();
        $compo = $partie->getComposition();
        $roles = $compo->getRolesCompo();
        $rolesData = array();
        //récupération ROLES FIXES
        foreach($roles as $r){
            for($i = 0; $i<$r->getQuantite();$i++) {
                array_push($rolesData, $r->getRole()->getNomRole());
            }
        }
        //récupération ROLES ALEATOIRES (CATOGIRES)
        $categories = $compo->getCategoriesCompo();
        foreach($categories as $r){
            for($i = 0; $i<$r->getQuantite();$i++) {
                array_push($rolesData, $r->getCategorie()->getNomCategorie());
            }
        }
        return $rolesData;
    }
}