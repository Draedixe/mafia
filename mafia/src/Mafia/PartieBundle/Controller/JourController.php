<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 11/03/2015
 * Time: 22:58
 */

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\Message;
use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class JourController extends Controller{

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


    public function voterPourAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        $id = $request->get("id");
        $pid = $request->get('premierid');
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            $userVote = $repositoryUser->find($id);

            if ($user != null) {
                if ($userVote != null) {
                    $partie = $user->getPartie();
                    if($partie != null) {
                        if ($partie->getId() == $userVote->getPartie()->getId()) {
                            if ($user->getId() != $userVote->getId()) {
                                if ($user->getUser()->getId() != $userVote->getUser()->getId()) {
                                    if ($userVote->getVivant()) {
                                        if($partie->getPhaseEnCours() == PhaseJeuEnum::JOUR){
                                            $ancien = $user->getVotePour();
                                            $chat = $partie->getChat();

                                            if ($ancien == $userVote) {
                                                $ancien = $user->getVotePour();
                                                $user->setVotePour(null);
                                                $em->persist($user);
                                                $em->flush();

                                                $this->messageSysteme($em,$chat,$user->getNom() . " annule son vote");
                                                $messages = $this->recevoirTousMessages($user,$pid);
                                                return new JsonResponse(array("messages" => $messages, "statut" => "SUCCESS", 'action' => "Voter", "ancien" => $ancien));
                                            } else {
                                                $user->setVotePour($userVote);
                                                $em->persist($user);
                                                $em->flush();
                                                $this->messageSysteme($em,$chat,$user->getNom() . " vote pour " . $userVote->getNom() );
                                                $messages = $this->recevoirTousMessages($user,$pid);
                                                if ($ancien != null) {
                                                    return new JsonResponse(array("messages" => $messages, "statut" => "SUCCESS", 'action' => "Annuler", "ancien" => $ancien->getId()));
                                                } else {
                                                    return new JsonResponse(array("messages" => $messages, "statut" => "SUCCESS", 'action' => "Annuler", "ancien" => ""));
                                                }
                                            }
                                        } else {
                                            return new JsonResponse(array("statut" => "BADVOTE"));
                                        }
                                    } else {
                                        return new JsonResponse(array("statut" => "BADVOTE"));
                                    }
                                } else {
                                    return new JsonResponse(array("statut" => "BADVOTE"));
                                }
                            } else {
                                return new JsonResponse(array("statut" => "BADVOTE"));
                            }
                        }else{
                            return new JsonResponse(array("statut" => "BADVOTE"));
                        }
                    } else {
                        return new JsonResponse(array("statut" => "BADVOTE"));
                    }
                } else {
                    return new JsonResponse(array("statut" => "BADVOTE"));
                }
            } else {
                return new JsonResponse(array("statut" => "BADVOTE"));
            }
        } else {
            return new JsonResponse(array("statut" => "BADVOTE"));
        }
    }

} 