<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Proxies\__CG__\Mafia\PartieBundle\Entity\UserPartie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Mafia\PartieBundle\Entity\Message;
use Mafia\PartieBundle\Service\recuperation_composition;

class JeuController extends Controller{

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

                $usersPartie = $repositoryUser->findBy(array("partie" => $partie, "vivant" => true));

                $enVieId = array();
                $enViePseudo = array();
                foreach ($usersPartie as $userEnVie) {
                    array_push($enVieId, $userEnVie->getId());
                    array_push($enViePseudo, $userEnVie->getNom());
                }

                //Formulaire pour le chat
                $formBuilder = $this->get('form.factory')->create();
                $formBuilder
                    ->add('message', 'text', array('label' => 'Message'));




                $chat = $partie->getChat();

                $repositoryMessage = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('MafiaPartieBundle:Message');


                $pid = 0;

                //$messages = $repositoryMessage->myFind($chat, $pid);

                //$dataMessage = array();
                $id = 0;
               /* foreach ($messages as $message) {
                    $dataMessage[$id] = array("id" => $message->getId(), "pseudo" => $message->getUser()->getUsername(), "message" => $message->getTexte(), "date" => $message->getDate());
                    $id++;
                }*/
                $dataMessage = $this->recevoirTousMessages($user, $id);

                //RECUPERATION DE LA COMPOSITION

                $rolesData = $this->get('recuperation_composition')->recupCompo($user);

                //TODO

                $monRoleData = null;
                $monRole = $user->getRole();
                if($monRole != null) {
                    $monRoleData = array("faction" => $monRole->getEnumFaction(), "nom" => $monRole->getNomRole(), "description" => $monRole->getDescription(), "descriptionPrincipale" => $monRole->getDescriptionPrincipale(), "capacite" => $monRole->getCapacite());
                }

                return $this->render('MafiaPartieBundle:Affichages:jeu.html.twig',
                    array(
                        "partie" => $partie,
                        "enVieId" => $enVieId,
                        "enViePseudo" => $enViePseudo,
                        "tempsRestant" => ($partie->getDureePhase() * 60) - ((new \DateTime())->getTimestamp() - $partie->getDebutPhase()->getTimestamp()),
                        'form' => $formBuilder->createView(),
                        "messages" => $dataMessage,
                        "roles" => $rolesData,
                        "monRole" => $monRoleData
                    )
                );
            } else {
                return $this->forward('MafiaUserBundle:Default:menu');
            }
        }
        return $this->forward('MafiaUserBundle:Default:menu');
    }

    public function razVotes()
    {

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

                $results = $query->getResult();
            }
        }

    }

    private function messageSysteme($em,$chat,$message){
        $newMessage = new Message();
        $newMessage->setType(0);
        $newMessage->setChat($chat);
        $newMessage->setDate(new \DateTime());
        $newMessage->setTexte($message);
        $newMessage->setUser(null);

        $em->persist($newMessage);
        $em->flush();
    }

    public function verifPhase()
    {
        $em = $this->getDoctrine()->getManager();

        $repositoryUser = $this->getDoctrine()
        ->getManager()
        ->getRepository('MafiaPartieBundle:UserPartie');

        //$user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $partie = $user->getPartie();
                $chat = $partie->getChat();
                $parametres = $partie->getParametres();

                $usersPartie = $repositoryUser->findBy(array("partie" => $partie, "vivant" => true));
                if (((new \DateTime())->getTimestamp() - $partie->getDebutPhase()->getTimestamp()) > ($partie->getDureePhase() * 60)) {

                    switch ($partie->getPhaseEnCours()) {
                        case PhaseJeuEnum::AUBE :
                            $partie->setPhaseEnCours(PhaseJeuEnum::DISCUSSION);
                            $partie->setDureePhase($parametres->getTempsDeDiscussion());
                            $this->messageSysteme($em,$chat,"C'est l'heure de discuter");
                            break;
                        case PhaseJeuEnum::NUIT :
                            $partie->setPhaseEnCours(PhaseJeuEnum::AUBE);
                            $partie->setDureePhase(0.2);
                            $this->messageSysteme($em,$chat,"C'est l'aube");
                            break;
                        case PhaseJeuEnum::DISCUSSION :
                            $this->razVotes();
                            $partie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                            $partie->setDureePhase($parametres->getDureeDuJour());
                            $this->messageSysteme($em,$chat,"C'est le jour");
                            break;
                        case PhaseJeuEnum::EXECUTION :
                            $this->razVotes();
                            $partie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                            $partie->setDureePhase($parametres->getDureeDeLaNuit());
                            $this->messageSysteme($em,$chat,"C'est la nuit");
                            break;
                        case PhaseJeuEnum::JOUR :
                            $this->razVotes();

                            $partie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                            $partie->setDureePhase($parametres->getDureeDeLaNuit());
                            $this->messageSysteme($em,$chat,"C'est la nuit");
                            break;
                        case PhaseJeuEnum::RESULTAT_VOTE :
                            $usersPartieNon = $repositoryUser->findBy(array("partie" => $partie, "vivant" => true, "voteTribunal" => 0));
                            $usersPartieOui = $repositoryUser->findBy(array("partie" => $partie, "vivant" => true, "voteTribunal" => 1));
                            if (count($usersPartieOui) > count($usersPartieNon)) {
                                $partie->setPhaseEnCours(PhaseJeuEnum::EXECUTION);
                                $partie->setDureePhase(0.2);
                            } else {
                                if ($partie->getTempsJourRestant() > 0) {
                                    $this->razVotes();
                                    $partie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                                    $partie->setDureePhase($partie->getTempsJourRestant());
                                    $this->messageSysteme($em,$chat,"C'est le jour");
                                } else {
                                    $this->razVotes();
                                    $partie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                                    $partie->setDureePhase($parametres->getDureeDeLaNuit());
                                    $this->messageSysteme($em,$chat,"C'est la nuit");
                                }
                            }
                            break;
                        case PhaseJeuEnum::TRIBUNAL_DEFENSE :
                            $partie->setPhaseEnCours(PhaseJeuEnum::TRIBUNAL_VOTE);
                            $partie->setDureePhase($parametres->getTempsTribunal());
                            $this->messageSysteme($em,$chat,"C'est l'heure du vote");
                            break;
                        case PhaseJeuEnum::TRIBUNAL_VOTE :
                            $partie->setPhaseEnCours(PhaseJeuEnum::RESULTAT_VOTE);
                            $partie->setDureePhase(0.25);
                            $this->messageSysteme($em,$chat,"Voici les résultats du vote");
                            break;

                    }
                    $partie->setDebutPhase(new \DateTime());

                }
                if ($partie->getPhaseEnCours() == PhaseJeuEnum::JOUR) {
                    $votes = array();

                    foreach ($usersPartie as $joueur) {
                        if ($joueur->getVotePour() != null) {
                            if (!isset($votes[$joueur->getVotePour()->getId()])) {
                                $votes[$joueur->getVotePour()->getId()] = 0;
                            }
                            $votes[$joueur->getVotePour()->getId()]++;
                        }
                    }
                    $majorite = count($usersPartie) + 1;
                    foreach ($votes as $vote) {
                        if ($vote > $majorite) {
                            $partie->setPhaseEnCours(PhaseJeuEnum::TRIBUNAL_DEFENSE);
                            $partie->setTempsJourRestant($parametres->getDureeDuJour() - (((new \DateTime())->getTimestamp() - $partie->getDebutPhase()->getTimestamp())));
                            $partie->setDureePhase($parametres->getTempsTribunal());
                            $this->messageSysteme($em,$chat,"C'est l'heure de se défendre");
                        }
                    }

                }
                $em->persist($partie);
                $em->flush();
                return $partie->getPhaseEnCours();
            }
        }
        return -1;
    }

    public function recevoirDureeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        //$user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
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
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::NUIT) {
                    return new JsonResponse(array("statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("statut" => "CHANGEMENT", 'phase' => $phase));
                }

            }
        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsExecutionAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        //$user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::EXECUTION) {
                    return new JsonResponse(array("statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("statut" => "CHANGEMENT", 'phase' => $phase));
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

                    $enVieId = array();
                    $enViePseudo = array();
                    foreach ($usersPartie as $userEnVie) {
                        $enVieId[] = $userEnVie->getId();
                        $enViePseudo[] = $userEnVie->getNom();
                    }
                    return new JsonResponse(array("messages" => $messages, "statut" => "SUCCESS", 'phase' => $phase, "enVieId" => $enVieId, "enViePseudo" => $enViePseudo));
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
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::TRIBUNAL_DEFENSE) {
                    return new JsonResponse(array("statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("statut" => "CHANGEMENT", 'phase' => $phase));
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
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::TRIBUNAL_VOTE) {
                    return new JsonResponse(array("statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("statut" => "CHANGEMENT", 'phase' => $phase));
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
                $phase = $this->verifPhase();
                if ($phase == PhaseJeuEnum::RESULTAT_VOTE) {
                    return new JsonResponse(array("statut" => "SUCCESS", 'phase' => $phase));
                } else {
                    return new JsonResponse(array("statut" => "CHANGEMENT", 'phase' => $phase));
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