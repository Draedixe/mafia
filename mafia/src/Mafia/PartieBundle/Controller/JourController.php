<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 11/03/2015
 * Time: 22:58
 */

namespace Mafia\PartieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class JourController extends Controller{

    public function voterPourAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        $id = $request->get("id");
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        //$user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        $userGlobal = $this->getUser();
        $user = $userGlobal->getUserCourant();
        $userVote = $repositoryUser->find($id);

        //TODO verifs
        if($user != null)
        {
            if($userVote != null)
            {
                if($user->getPartie()->getId() == $userVote->getPartie()->getId())
                {
                    if($user->getId() != $userVote->getId())
                    {
                        if($user->getUser()->getId() != $userVote->getUser()->getId())
                        {
                            if($userVote->getVivant())
                            {
                                $ancien = $user->getVotePour();

                                if ($ancien == $userVote) {
                                    $ancien = $user->getVotePour();
                                    $user->setVotePour(null);
                                    $em->persist($user);
                                    $em->flush();
                                    return new JsonResponse(array("statut" => "SUCCESS", 'action' => "Voter", "ancien" => $ancien));
                                }
                                else
                                {
                                    $user->setVotePour($userVote);
                                    $em->persist($user);
                                    $em->flush();
                                    if($ancien != null)
                                    {
                                        return new JsonResponse(array("statut" => "SUCCESS",'action' => "Annuler", "ancien" => $ancien->getId()));
                                    }
                                    else
                                    {
                                        return new JsonResponse(array("statut" => "SUCCESS",'action' => "Annuler", "ancien" => ""));
                                    }
                                }
                            }
                            else
                            {
                                return new JsonResponse(array("statut" => "BADVOTE"));
                            }
                        }
                        else
                        {
                            return new JsonResponse(array("statut" => "BADVOTE"));
                        }
                    }
                    else
                    {
                        return new JsonResponse(array("statut" => "BADVOTE"));
                    }
                }
                else
                {
                    return new JsonResponse(array("statut" => "BADVOTE"));
                }
            }
            else
            {
                return new JsonResponse(array("statut" => "BADVOTE"));
            }
        }
        else
        {
            return new JsonResponse(array("statut" => "BADVOTE"));
        }



    }

} 