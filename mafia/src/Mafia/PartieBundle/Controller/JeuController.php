<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Proxies\__CG__\Mafia\PartieBundle\Entity\UserPartie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class JeuController extends Controller{

    public function debutPartieAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => 1));

        $partie = $user->getPartie();
        $usersPartie = $repositoryUser->findBy(array("partie"=> $partie, "vivant" => true));

        $enVie = array();
        foreach($usersPartie as $userEnVie)
        {
            $enVie[$userEnVie->getId()] = $userEnVie->getNom();
        }

        return $this->render('MafiaPartieBundle:Affichages:jeu.html.twig',
            array(
                "partie" => $partie,
                "enVie" => $enVie//json_encode($enVie)
            )
        );
    }

    public function razVotes()
    {

        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $partie = $user->getPartie();
            $qB = $this->getDoctrine()->getManager()->createQueryBuilder();
            $qB ->update('MafiaPartieBundle:UserPartie', 'j')
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

    public function verifPhase()
    {
        $em = $this->getDoctrine()->getManager();

        $repositoryUser = $this->getDoctrine()
        ->getManager()
        ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));

        if($user != null) {
            $partie = $user->getPartie();
            $parametres = $partie->getParametres();

            $usersPartie = $repositoryUser->findBy(array("partie"=> $partie, "vivant" => true));
            if (((new \DateTime())->getTimestamp() - $partie->getDebutPhase()->getTimestamp()) > ($partie->getDureePhase()*60)) {

                switch ($partie->getPhaseEnCours()) {
                    case PhaseJeuEnum::AUBE :
                        $partie->setPhaseEnCours(PhaseJeuEnum::DISCUSSION);
                        $partie->setDureePhase($parametres->getTempsDeDiscussion());
                        break;
                    case PhaseJeuEnum::NUIT :
                        $partie->setPhaseEnCours(PhaseJeuEnum::AUBE);
                        $partie->setDureePhase(0.2);
                        break;
                    case PhaseJeuEnum::DISCUSSION :
                        $this->razVotes();
                        $partie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                        $partie->setDureePhase($parametres->getDureeDuJour());
                        break;
                    case PhaseJeuEnum::EXECUTION :
                        $this->razVotes();
                        $partie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                        $partie->setDureePhase($parametres->getDureeDeLaNuit());
                        break;
                    case PhaseJeuEnum::JOUR :
                        $this->razVotes();

                        $partie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                        $partie->setDureePhase($parametres->getDureeDeLaNuit());
                        break;
                    case PhaseJeuEnum::RESULTAT_VOTE :
                        $usersPartieNon = $repositoryUser->findBy(array("partie"=> $partie, "vivant" => true, "voteTribunal" => 0));
                        $usersPartieOui = $repositoryUser->findBy(array("partie"=> $partie, "vivant" => true, "voteTribunal" => 1));
                        if(count($usersPartieOui) > count($usersPartieNon) )
                        {
                            $partie->setPhaseEnCours(PhaseJeuEnum::EXECUTION);
                            $partie->setDureePhase(0.2);
                        }
                        else
                        {
                            if($partie->getTempsJourRestant() > 0)
                            {
                                $this->razVotes();
                                $partie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                                $partie->setDureePhase($partie->getTempsJourRestant());
                            }
                            else
                            {
                                $this->razVotes();
                                $partie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                                $partie->setDureePhase($parametres->getDureeDeLaNuit());
                            }
                        }
                        break;
                    case PhaseJeuEnum::TRIBUNAL_DEFENSE :
                        $partie->setPhaseEnCours(PhaseJeuEnum::TRIBUNAL_VOTE);
                        $partie->setDureePhase($parametres->getTempsTribunal());
                        break;
                    case PhaseJeuEnum::TRIBUNAL_VOTE :
                        $partie->setPhaseEnCours(PhaseJeuEnum::RESULTAT_VOTE);
                        $partie->setDureePhase(0.25);
                        break;

                }
                $partie->setDebutPhase(new \DateTime());

            }
            if($partie->getPhaseEnCours() == PhaseJeuEnum::JOUR)
            {
                $votes = array();
                foreach($usersPartie as $joueur)
                {
                    if($joueur->getVotePour() != null)
                    {
                        if($votes[$joueur->getVotePour()->getId()] == null)
                        {
                            $votes[$joueur->getVotePour()->getId()] = 0;
                        }
                        $votes[$joueur->getVotePour()->getId()]++;
                    }
                }
                $majorite = count($usersPartie) +1;
                foreach($votes as $vote)
                {
                    if($vote > $majorite)
                    {
                        $partie->setPhaseEnCours(PhaseJeuEnum::TRIBUNAL_DEFENSE);
                        $partie->setTempsJourRestant($parametres->getDureeDuJour() - (((new \DateTime())->getTimestamp() - $partie->getDebutPhase()->getTimestamp())) );
                        $partie->setDureePhase($parametres->getTempsTribunal());

                    }
                }

            }
            $em->persist($partie);
            $em->flush();
            return $partie->getPhaseEnCours();
        }
        return -1;
    }

    public function recevoirInformationsNuitAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $phase = $this->verifPhase();
            if($phase == PhaseJeuEnum::NUIT)
            {
                return new JsonResponse(array("statut" => "SUCCESS",'phase' => $phase));
            }
            else
            {
                return new JsonResponse(array("statut" => "CHANGEMENT",'phase' => $phase));
            }

        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsExecutionAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $phase = $this->verifPhase();
            if($phase == PhaseJeuEnum::EXECUTION)
            {
                return new JsonResponse(array("statut" => "SUCCESS",'phase' => $phase));
            }
            else
            {
                return new JsonResponse(array("statut" => "CHANGEMENT",'phase' => $phase));
            }

        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsAubeAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $phase = $this->verifPhase();
            if($phase == PhaseJeuEnum::AUBE)
            {
                return new JsonResponse(array("statut" => "SUCCESS",'phase' => $phase));
            }
            else
            {
                return new JsonResponse(array("statut" => "CHANGEMENT",'phase' => $phase));
            }

        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsDiscussionAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $phase = $this->verifPhase();
            if($phase == PhaseJeuEnum::DISCUSSION)
            {
                return new JsonResponse(array("statut" => "SUCCESS",'phase' => $phase));
            }
            else
            {
                return new JsonResponse(array("statut" => "CHANGEMENT",'phase' => $phase));
            }

        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsJourAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));

        $usersPartie = $repositoryUser->findBy(array("partie"=> $user->getPartie(), "vivant" => true));

        if($user != null) {
            $phase = $this->verifPhase();
            if($phase == PhaseJeuEnum::JOUR)
            {
                return new JsonResponse(array("statut" => "SUCCESS",'phase' => $phase, "enVie" => $usersPartie));
            }
            else
            {
                return new JsonResponse(array("statut" => "CHANGEMENT",'phase' => $phase));
            }

        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsTribunalDefenseAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $phase = $this->verifPhase();
            if($phase == PhaseJeuEnum::TRIBUNAL_DEFENSE)
            {
                return new JsonResponse(array("statut" => "SUCCESS",'phase' => $phase));
            }
            else
            {
                return new JsonResponse(array("statut" => "CHANGEMENT",'phase' => $phase));
            }

        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsTribunalVoteAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $phase = $this->verifPhase();
            if($phase == PhaseJeuEnum::TRIBUNAL_VOTE)
            {
                return new JsonResponse(array("statut" => "SUCCESS",'phase' => $phase));
            }
            else
            {
                return new JsonResponse(array("statut" => "CHANGEMENT",'phase' => $phase));
            }

        }
        return new JsonResponse(array("statut" => "FAIL"));
    }

    public function recevoirInformationsVoteResultatAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user != null) {
            $phase = $this->verifPhase();
            if($phase == PhaseJeuEnum::RESULTAT_VOTE)
            {
                return new JsonResponse(array("statut" => "SUCCESS",'phase' => $phase));
            }
            else
            {
                return new JsonResponse(array("statut" => "CHANGEMENT",'phase' => $phase));
            }

        }
        return new JsonResponse(array("statut" => "FAIL"));
    }
}