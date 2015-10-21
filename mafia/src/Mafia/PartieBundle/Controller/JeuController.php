<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Proxies\__CG__\Mafia\PartieBundle\Entity\UserPartie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Mafia\PartieBundle\Entity\Message;
use Mafia\PartieBundle\Service\recuperation_composition;

class JeuController extends FunctionsController{

    public function debutPartieAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $em = $this->getDoctrine()->getManager();
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $partie = $user->getPartie();
                //Si la partie est déja terminée
                if ($partie->isTerminee()) {
                    $userGlobal->setUserCourant(NULL);
                    $em->persist($userGlobal);
                    return $this->forward('MafiaUserBundle:Default:menu');
                }

                $usersPartie = $repositoryUser->findBy(array("partie" => $partie));

                $usersPartieVivants = $repositoryUser->findBy(array("partie" => $partie, "vivant" => true));
                $votes = array();
                foreach ($usersPartieVivants as $joueur) {
                    $votes[$joueur->getId()] = 0;
                }
                foreach ($usersPartieVivants as $joueur) {
                    if ($joueur->getVotePour() != null) {
                        $votes[$joueur->getVotePour()->getId()]++;
                    }
                }

                $joueurs = array();
                foreach ($usersPartie as $userEnVie) {
                    if(isset($votes[$userEnVie->getId()])){
                        array_push($joueurs, array("role"=> "???" ,"id" => $userEnVie->getId(), "nom" => $userEnVie->getNom(), "vivant" => $userEnVie->getVivant(), "nbVotes" => $votes[$userEnVie->getId()]));
                    } else {
                        array_push($joueurs, array("role"=>$userEnVie->getRole()->getNomRole() ,"id" => $userEnVie->getId(), "nom" => $userEnVie->getNom(), "vivant" => $userEnVie->getVivant(), "nbVotes" => 0));
                    }
                }

                //Formulaire pour le chat
                $formBuilder = $this->get('form.factory')->create();
                $formBuilder
                    ->add('message', 'text', array('label' => 'Message'));


                $id = 0;
                $dataMessage = $this->recevoirTousMessages($user, $id);

                //RECUPERATION DE LA COMPOSITION
                $rolesData = $this->get('recuperation_composition')->recupCompo($user);



                $monRoleData = null;
                $monRole = $user->getRole();
                if($monRole != null) {
                    $monRoleData = array("faction" => $monRole->getEnumFaction(), "nom" => $monRole->getNomRole(), "description" => $monRole->getDescription(), "descriptionPrincipale" => $monRole->getDescriptionPrincipale(), "capacite" => $monRole->getCapacite());
                }
                $monId = $user->getId();
                if($user->getVotePour() != null){
                    $votePour = $user->getVotePour()->getId();
                } else{
                    $votePour = -1;
                }
                return $this->render('MafiaPartieBundle:Affichages:jeu.html.twig',
                    array(
                        "partie" => $partie,
                        "joueurs" => $joueurs,
                        "tempsRestant" => ($partie->getDureePhase() * 60) - ((new \DateTime())->getTimestamp() - $partie->getDebutPhase()->getTimestamp()),
                        "form" => $formBuilder->createView(),
                        "messages" => $dataMessage,
                        "roles" => $rolesData,
                        "monRole" => $monRoleData,
                        "monId" => $monId,
                        "votePour" => $votePour
                    )
                );
            } else {
                return $this->forward('MafiaUserBundle:Default:menu');
            }
        }
        return $this->forward('MafiaUserBundle:Default:menu');
    }

    public function razVotes(){
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $partie = $user->getPartie();
                $qB = $this->getDoctrine()->getManager()->createQueryBuilder();
                $qB->update('MafiaPartieBundle:UserPartie', 'j')
                    ->set('j.votePour', '?1')
                    ->set('j.voteTribunal', '?2')
                    ->where('j.partie = ?3')
                    ->andWhere('j.vivant = ?4')
                    ->setParameter(1, null)
                    ->setParameter(2, 2)
                    ->setParameter(3, $partie)
                    ->setParameter(4, true);

                $query = $qB->getQuery();

                $query->getResult();
            }
        }
    }

    public function recevoirDureeAction()
    {
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $partie = $user->getPartie();
                return new JsonResponse(array("statut" => "SUCCESS", 'dureePhase' => ($partie->getDureePhase() * 60) - ((new \DateTime())->getTimestamp() - $partie->getDebutPhase()->getTimestamp())));
            } else {
                return new JsonResponse(array("statut" => "FAIL"));
            }
        }
    }

    public function recevoirInformationsNuitAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        //$user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $request = $this->container->get('request');
                $id = $request->get('premierid');
                $messages = $this->recevoirTousMessages($user,$id);
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::NUIT) {
                    return new JsonResponse(array("messages" => $messages, "statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("messages" => $messages, "statut" => "CHANGEMENT", 'phase' => $phase));
                }

            }
        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsExecutionAction()
    {
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $request = $this->container->get('request');
                $id = $request->get('premierid');
                $messages = $this->recevoirTousMessages($user,$id);
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::EXECUTION) {
                    return new JsonResponse(array("messages" => $messages, "statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("messages" => $messages, "statut" => "CHANGEMENT", 'phase' => $phase));
                }
            }
        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsAubeAction()
    {

        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $request = $this->container->get('request');
                $id = $request->get('premierid');
                $messages = $this->recevoirTousMessages($user,$id);
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::AUBE) {
                    return new JsonResponse(array("messages" => $messages,"statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("messages" => $messages,"statut" => "CHANGEMENT", 'phase' => $phase));
                }

            }
        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsDiscussionAction()
    {
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $request = $this->container->get('request');
                $id = $request->get('premierid');
                $messages = $this->recevoirTousMessages($user,$id);
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::DISCUSSION) {
                    return new JsonResponse(array("messages" => $messages,"statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("messages" => $messages,"statut" => "CHANGEMENT", 'phase' => $phase));
                }

            }
        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsJourAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            $request = $this->container->get('request');
            $id = $request->get('premierid');

            if ($user != null) {
                $messages = $this->recevoirTousMessages($user,$id);
                $usersPartie = $repositoryUser->findBy(array("partie" => $user->getPartie(), "vivant" => true));
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::JOUR) {
                    $votes = array();
                    foreach ($usersPartie as $joueur) {
                        $votes[$joueur->getId()] = 0;
                    }
                    foreach ($usersPartie as $joueur) {
                        if ($joueur->getVotePour() != null) {
                            $votes[$joueur->getVotePour()->getId()]++;
                        }
                    }

                    $usersPartieTous = $repositoryUser->findBy(array("partie" => $user->getPartie()));
                    $enVieId = array();
                    $enViePseudo = array();
                    $joueursVivants = array();
                    $joueursRoles = array();
                    foreach ($usersPartieTous as $userEnVie) {
                        $enVieId[] = $userEnVie->getId();
                        $enViePseudo[] = $userEnVie->getNom();
                        $joueursVivants[] = $userEnVie->getVivant();
                        if($userEnVie->getVivant()){
                            $joueursRoles[] = "???";
                        } else{
                            $joueursRoles[] = $userEnVie->getRole()->getNomRole();
                        }
                    }
                    return new JsonResponse(array("joueursRoles"=>$joueursRoles, "joueursVivants"=>$joueursVivants, "votes"=>$votes, "messages" => $messages, "statut" => "SUCCESS", 'phase' => $phase, "enVieId" => $enVieId, "enViePseudo" => $enViePseudo));
                } else {
                    return new JsonResponse(array("messages" => $messages, "statut" => "CHANGEMENT", 'phase' => $phase));
                }

            }
        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    private function recevoirTousMessages($user,$id){
        $repositoryMessage = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Message');


        $chat = $user->getPartie()->getChat();
        $messages = $repositoryMessage->myFind($chat, $id);

        $data = array();
        $id = 0;
        foreach ($messages as $message) {
            if($message->getType() == 0 && $message->getRecepteur() == null) {
                if ($message->getUser() == null) {
                    $data[$id] = array("id" => $message->getId(), "date" => $message->getDate(), "pseudo" => "SYSTEME", "message" => $message->getTexte(), "systeme" => true);
                } else {
                    $data[$id] = array("id" => $message->getId(), "date" => $message->getDate(), "pseudo" => $message->getUser()->getUsername(), "message" => $message->getTexte(),"systeme" => false);
                }
                $id++;
            }
        }

        return $data;
    }

    public function recevoirInformationsTribunalDefenseAction()
    {
        $userGlobal = $this->getUser();
        if ($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $request = $this->container->get('request');
                $id = $request->get('premierid');
                $messages = $this->recevoirTousMessages($user,$id);
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::TRIBUNAL_DEFENSE) {
                    return new JsonResponse(array("messages" => $messages, "statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("messages" => $messages, "statut" => "CHANGEMENT", 'phase' => $phase));
                }

            }
        }
        return new JsonResponse(array("statut" => "FAIL"));
    }


    public function recevoirInformationsTribunalVoteAction()
    {
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $request = $this->container->get('request');
                $id = $request->get('premierid');
                $messages = $this->recevoirTousMessages($user,$id);
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::TRIBUNAL_VOTE) {
                    return new JsonResponse(array("messages" => $messages, "statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("messages" => $messages, "statut" => "CHANGEMENT", 'phase' => $phase));
                }

            }
        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsVoteResultatAction()
    {
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $request = $this->container->get('request');
                $id = $request->get('premierid');
                $messages = $this->recevoirTousMessages($user,$id);
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::RESULTAT_VOTE) {
                    return new JsonResponse(array("messages" => $messages, "statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("messages" => $messages, "statut" => "CHANGEMENT", 'phase' => $phase));
                }

            }
        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function suicideAction(){

        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $em = $this->getDoctrine()->getManager();
                $user->setVivant(false);
                $em->persist($user);
                $em->flush();
            }
        }
        return $this->forward('MafiaUserBundle:Default:menu');
    }

    public function lastWordAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        $lastWord = $request->get("lastWord");

        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();

            $user->setLastWord($lastWord);
            $em->persist($user);
            $em->flush();
        }
        return new JsonResponse(array("statut" => "SUCCESS"));
    }

    public function deathNoteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        $deathNote = $request->get("deathNote");

        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();

            $user->setDeathNote($deathNote);
            $em->persist($user);
            $em->flush();
        }
        return new JsonResponse(array("statut" => "SUCCESS"));
    }

    public function envoyerMessageIGAction(){
        $request = $this->container->get('request');
        $message = $request->get('message');
        if($message != null) {
            $em = $this->getDoctrine()->getManager();

            $userGlobal = $this->getUser();
            if($userGlobal != null) {
                $user = $userGlobal->getUserCourant();
                if ($user == null) {
                    return new JsonResponse(array("error" => true));
                }
                else {
                    $partie = $user->getPartie();
                    $phase = $partie->getPhaseEnCours();
                    if ($phase == PhaseJeuEnum::JOUR || $phase == PhaseJeuEnum::DISCUSSION || $phase == PhaseJeuEnum::TRIBUNAL_VOTE) {
                        $chat = $partie->getChat();

                        $newMessage = new Message();
                        $newMessage->setType(0);
                        $newMessage->setChat($chat);
                        $newMessage->setDate(new \DateTime());
                        $newMessage->setTexte(strip_tags($message));
                        $newMessage->setUser($this->getUser());

                        $em->persist($newMessage);
                        $em->flush();
                        return new JsonResponse(array("error" => false));
                    }
                    else if($phase == PhaseJeuEnum::NUIT){
                        return new JsonResponse(array("error" => true));
                    }
                }
            }
        }
        return new JsonResponse(array("error" => true));
    }
}