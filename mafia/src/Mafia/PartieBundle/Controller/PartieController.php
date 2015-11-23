<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\Chat;
use Mafia\PartieBundle\Entity\DebutPartieEnum;
use Mafia\PartieBundle\Entity\Message;
use Mafia\PartieBundle\Entity\Parametres;
use Mafia\PartieBundle\Entity\Partie;
use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Mafia\PartieBundle\Entity\UserPartie;
use Mafia\RolesBundle\Entity\RolesEnum;
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

    //Quitter la partie au niveau du chat (partie non lancée)
    public function quitterPartieAction(){
        $userDansAutrePartie = null;
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');
        $em = $this->getDoctrine()->getManager();
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            //On récupère le userCourant
            $userDansAutrePartie = $userGlobal->getUserCourant();
            //Si on récupère quelquechose (si on ne trouve rien on renvoie le user sur le menu)
            if ($userDansAutrePartie != null) {
                //On trouve le user dans une partie deja terminée, on met a jour le user
                if ($userDansAutrePartie->getPartie()->isTerminee()) {
                    $userGlobal->setUserCourant(NULL);
                    $userDansAutrePartie = null;
                    return $this->forward('MafiaUserBundle:Default:menu');
                }
                //Si le user est dans une partie commencee et qu'il est encore vivant, il y retourne
                if ($userDansAutrePartie->getPartie()->isCommencee() && $userDansAutrePartie->getVivant()) {
                    return $this->forward('MafiaPartieBundle:Jeu:debutPartie');
                }

                $partie = $userDansAutrePartie->getPartie();
                //Si le user est la createur de la partie
                if ($partie->getCreateur() == $userDansAutrePartie) {
                    //Le user n'est plus créateur
                    $partie->setCreateur(NULL);
                    //On supprime le user
                    $userGlobal->setUserCourant(NULL);
                    $em->persist($userGlobal);
                    $em->remove($userDansAutrePartie);
                    $em->persist($partie);
                    $em->flush();
                    //Remplacement du createur par un joueur au hasard
                    $userCreateur = $repositoryUser->findOneBy(array("partie" => $partie));
                    $partie->setCreateur($userCreateur);
                    $em->persist($partie);
                    $em->flush();
                } else {
                    $userGlobal->setUserCourant(NULL);
                    $em->persist($userGlobal);
                    $em->remove($userDansAutrePartie);
                    $em->flush();
                }
            }
        }
        return $this->forward('MafiaUserBundle:Default:menu');
    }

    //Un utilisateur rejoint / crée une partie
    public function jouerPartie($type){
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            ////DEBUT ON FAIT LE PLEIN DE REPOSITORY
            $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:UserPartie');
            //$userResponse = $repositoryUser->findBy(array("user" => $this->getUser()));

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


            //On récupère le userCourant
            $userDansAutrePartie = $userGlobal->getUserCourant();
            //Si on récupère quelquechose (si on ne trouve rien on crée un userPartie)
            if($userDansAutrePartie != null) {
                //On trouve le user dans une partie deja terminée, on met a jour le user
                if ($userDansAutrePartie->getPartie()->isTerminee() || !$userDansAutrePartie->getVivant()) {
                    $userGlobal->setUserCourant(NULL);
                    $userDansAutrePartie = null;
                }
                //Si le user est dans une partie commencee et qu'il est encore vivant, il y retourne
                else if ($userDansAutrePartie->getPartie()->isCommencee() && $userDansAutrePartie->getVivant()) {
                    return $this->forward('MafiaPartieBundle:Jeu:debutPartie');
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

                    $partieChoisie = new Partie();
                    $partieChoisie->setParametres($param);
                    $partieChoisie->setNomPartie("Partie de " . $this->getUser()->getUsername());
                    $debutDuJeu = $param->getDebutDuJeu();
                    if($debutDuJeu == DebutPartieEnum::JOUR){
                        $partieChoisie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                    }
                    else if($debutDuJeu == DebutPartieEnum::JOUR_SANS_LYNCHAGE){
                        $partieChoisie->setPhaseEnCours(PhaseJeuEnum::JOUR_SANS_VOTE);
                    }
                    else{
                        $partieChoisie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                    }

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
                $userGlobal->setUserCourant($userPartie);
                $userPartie->setPartie($partieChoisie);
                $userPartie->setUser($this->getUser());
                $userPartie->setNom($this->getUser()->getUsername());

                $em->persist($userPartie);
                $em->persist($userGlobal);
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

            $user = $userGlobal->getUserCourant();
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
                if($message->getUser() != null) {
                    $data[$id] = array("id" => $message->getId(), "pseudo" => $message->getUser()->getUsername(), "message" => $message->getTexte());
                    $id++;
                }
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
            $rolesData = $this->get('recuperation_composition')->recupCompo($user);

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

        //$user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            $partie = $user->getPartie();

            $userList = $repositoryUser->findBy(array("partie" => $partie));

            if ($user != null) {
                if ($user == $partie->getCreateur()) {
                    //On vérifie que la partie est bien pleine
                    $nbJoueurs = count($userList);
                    if ($nbJoueurs == $partie->getNombreJoueursMax()) {
                        $compo = $partie->getComposition();
                        $rolesCompo = $compo->getRolesCompo();
                        $cat = $compo->getCategoriesCompo();
                        $nbRoles = 0;
                        foreach ($rolesCompo as $r) {
                            $nbRoles = $nbRoles + $r->getQuantite();
                        }
                        foreach ($cat as $r) {
                            $nbRoles = $nbRoles + $r->getQuantite();
                        }
                        //On vérifie qu'il y a autant de roles que de joueurs
                        if ($nbJoueurs == $nbRoles) {
                            $em = $this->getDoctrine()->getManager();
                            $partie->setCommencee(true);
                            $chat = new Chat();
                            $partie->setChat($chat);

                            $newMessage = new Message();
                            $newMessage->setType(0);
                            $newMessage->setChat($chat);
                            $newMessage->setDate(new \DateTime());
                            $newMessage->setTexte("Début de la partie");
                            $newMessage->setUser(null);

                            //Liste des roles à affecter aux joueurs
                            $rolesAffecter = array();
                            $importances = $compo->getImportances();
                            //Transformation des categories en roles
                            $imp_temp = array();

                            //Pour les roles compo
                            foreach($rolesCompo as $rc){
                                for($i = 0; $i < $rc->getQuantite(); $i++){
                                    array_push($rolesAffecter, $rc->getRole());
                                }
                            }

                            //Pour toutes les CategoriesCompo contenues dans la composition
                            foreach ($cat as $c) {
                                $roles = $c->getCategorie()->getRoles();
                                //Pour chaque role
                                foreach($roles as $r){
                                    //Si c'est un role unique
                                    $trouveDejaDefinie = false;
                                    if($r->isRoleUnique()){
                                        //On cherche si il est déja dans la composition
                                        foreach($rolesAffecter as $raa){
                                            if($raa == $r){
                                                $trouveDejaDefinie = true;
                                                break;
                                            }
                                        }
                                    }

                                    if(!$trouveDejaDefinie) {
                                        $trouve = false;
                                        //On regarde s'il y a une importance qui correspond
                                        foreach ($importances as $i) {
                                            if ($r == $i->getRole()) {
                                                array_push($imp_temp, array($r, $i->getValeur()));
                                                $trouve = true;
                                                break;
                                            }
                                        }
                                        //Si on n'en trouve pas on la met à 100
                                        if (!$trouve) {
                                            array_push($imp_temp, array($r, 100));
                                        }
                                    }
                                }

                                $totalImportance = 0;
                                foreach($imp_temp as $imp){
                                    $totalImportance = $totalImportance + $imp[1];
                                }

                                $random = rand(0, $totalImportance-1);

                                $temp_min = 0;
                                $temp_max = 0;
                                //On choisit le rôle au hasard avec l'importance du rôle
                                foreach($imp_temp as $imp){
                                    $temp_min = $temp_max;
                                    $temp_max = $temp_max + $imp[1];
                                    if($random >= $temp_min && $random < $temp_max){
                                        array_push($rolesAffecter, $imp[0]);
                                        break;
                                    }
                                }
                            }



                            //Affectation des roles
                            $nbR = count($rolesAffecter);
                            for($cpt = 0; $cpt<$nbR; ++$cpt){
                                $random2 = rand(0, count($rolesAffecter)-1-$cpt);
                                $userList[$cpt]->setRole($rolesAffecter[$random2]);
                                //Nb d'utilisation du gilet pare balles
                                if($rolesAffecter[$random2]->getEnumRole() == RolesEnum::CITOYEN){
                                    $userList[$cpt]->setCapaciteRestante(1);
                                }
                                unset($rolesAffecter[$random2]);
                                $rolesAffecter = array_values($rolesAffecter);
                                $em->persist($userList[$cpt]);
                            }

                            $partie->setDebutPhase(new \DateTime());
                            $em->persist($newMessage);
                            $em->persist($chat);
                            $em->persist($partie);
                            $em->flush();
                            return new JsonResponse(array('lancer' => true, 'test' => count($rolesAffecter)));
                        } else {
                            return new JsonResponse(array('lancer' => false, 'compo' => true));
                        }
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
        //$user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
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
        }
        return new JsonResponse();
    }

    public function changerCompositionAction(){
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');
        $em = $this->getDoctrine()->getManager();
        //$user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
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
        }
        return new JsonResponse();
    }

    public function getCompositionAction(){
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        //$user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $rolesData = $this->get('recuperation_composition')->recupCompo($user);
                return new JsonResponse(array('roles' => $rolesData));
            }
        }
        return new JsonResponse();
    }


}