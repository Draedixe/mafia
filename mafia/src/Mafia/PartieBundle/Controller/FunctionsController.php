<?php

namespace Mafia\PartieBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Mafia\PartieBundle\Entity\Message;

class FunctionsController extends Controller{
    protected function verifPhase()
    {
        $em = $this->getDoctrine()->getManager();

        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

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
                    $majorite = floor(count($usersPartie)/2) + 1;
                    foreach ($votes as $vote) {
                        if ($vote >= $majorite) {
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

    protected function messageSysteme($em,$chat,$message){
        $newMessage = new Message();
        $newMessage->setType(0);
        $newMessage->setChat($chat);
        $newMessage->setDate(new \DateTime());
        $newMessage->setTexte($message);
        $newMessage->setUser(null);

        $em->persist($newMessage);
        $em->flush();
    }

}
