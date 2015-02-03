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

        return $this->render('MafiaPartieBundle:Affichages:jeu.html.twig',
            array(
                "partie" => $partie
            )
        );
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
            if (((new \DateTime())->getTimestamp() - $partie->getDebutPhase()->getTimestamp()) < ($partie->getDureePhase()*60)) {

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
                        $partie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                        $partie->setDureePhase($parametres->getDureeDuJour());
                        break;
                    case PhaseJeuEnum::EXECUTION :
                        $partie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                        $partie->setDureePhase($parametres->getDureeDeLaNuit());
                        break;
                    case PhaseJeuEnum::JOUR :
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
                                $partie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                                $partie->setDureePhase($partie->getTempsJourRestant());
                            }
                            else
                            {
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
                        $partie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                        $partie->setDureePhase($partie->getTempsJourRestant());

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
        $em = $this->getDoctrine()->getManager();
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));

        $partie = $user->getPartie();
        $em->flush();
        return new JsonResponse(array('phase' => $partie->getPhase()));
    }

    public function recevoirInformationsExecutionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));

        $partie = $user->getPartie();


        $em->flush();
        return new JsonResponse(array('phase' => $partie->getPhase()));
    }

    public function recevoirInformationsAubeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));

        $partie = $user->getPartie();


        $em->flush();
        return new JsonResponse(array('phase' => $partie->getPhase()));
    }

    public function recevoirInformationsDiscussionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));

        $partie = $user->getPartie();


        $em->flush();
        return new JsonResponse(array('phase' => $partie->getPhase()));
    }

    public function recevoirInformationsJourAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));

        $partie = $user->getPartie();


        $em->flush();
        return new JsonResponse(array('phase' => $partie->getPhase()));
    }
}