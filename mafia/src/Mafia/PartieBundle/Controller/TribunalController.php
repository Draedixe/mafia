<?php

namespace Mafia\PartieBundle\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Mafia\PartieBundle\Entity\PhaseJeuEnum;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TribunalController extends Controller{
    public function voterPourAction(){
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        $oui = $request->get('oui');
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $partie = $user->getPartie();
                if($partie != null) {
                    if($partie->getAccuse() != null){
                        if($user->getId() != $partie->getAccuse()->getId()){
                            if($partie->getPhaseEnCours() == PhaseJeuEnum::TRIBUNAL_VOTE){
                                $vote = $user->getVoteTribunal();
                                if($oui == "true"){
                                    if($vote == 0){
                                        $user->setVoteTribunal(1);
                                        $em->persist($user);
                                        $em->flush();
                                        return new JsonResponse(array("statut" => "SUCCESS", 'action' => "Oui"));
                                    } else if($vote == 1){
                                        $user->setVoteTribunal(2);
                                        $em->persist($user);
                                        $em->flush();
                                        return new JsonResponse(array("statut" => "SUCCESS", 'action' => "Annuler"));
                                    } else {
                                        $user->setVoteTribunal(1);
                                        $em->persist($user);
                                        $em->flush();
                                        return new JsonResponse(array("statut" => "SUCCESS", 'action' => "Oui"));
                                    }
                                } else {
                                    if ($vote == 0) {
                                        $user->setVoteTribunal(2);
                                        $em->persist($user);
                                        $em->flush();
                                        return new JsonResponse(array("statut" => "SUCCESS", 'action' => "Annuler"));
                                    } else if ($vote == 1) {
                                        $user->setVoteTribunal(0);
                                        $em->persist($user);
                                        $em->flush();
                                        return new JsonResponse(array("statut" => "SUCCESS", 'action' => "Non"));
                                    } else {
                                        $user->setVoteTribunal(0);
                                        $em->persist($user);
                                        $em->flush();
                                        return new JsonResponse(array("statut" => "SUCCESS", 'action' => "Non"));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return new JsonResponse(array("statut" => "BADVOTE", 'action' => "Annuler"));
    }
} 