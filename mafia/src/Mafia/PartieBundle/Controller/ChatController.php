<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatController extends Controller{

    public function envoyerMessageAction(){
        $request = $this->container->get('request');
        $message = $request->get('message');
        if($message != null) {
            $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:UserPartie');
            $em = $this->getDoctrine()->getManager();

            $userGlobal = $this->getUser();
            if($userGlobal != null) {
                $user = $userGlobal->getUserCourant();
                if ($user == null) {
                    return new JsonResponse(array('messages' => array(), 'users' => array()));
                }
                $partie = $user->getPartie();
                $chat = $partie->getChat();

                $newMessage = new Message();
                $newMessage->setChat($chat);
                $newMessage->setDate(new \DateTime());
                $newMessage->setTexte(strip_tags($message));
                $newMessage->setUser($this->getUser());

                $em->persist($newMessage);
                $em->flush();
            }
        }
        return new JsonResponse();
    }

    public function recevoirMessageAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $userGlobal = $this->getUser();
        if ($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user == null) {
                return new JsonResponse(array('messages' => array(), 'users' => array()));
            }
            $partie = $user->getPartie();
            $chat = $partie->getChat();

            $repositoryMessage = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaPartieBundle:Message');

            $request = $this->container->get('request');
            $id = $request->get('premierid');

            $messages = $repositoryMessage->myFind($chat, $id);

            $data = array();
            $id = 0;
            foreach ($messages as $message) {
                $data[$id] = array("id" => $message->getId(), "pseudo" => $message->getUser()->getUsername(), "message" => $message->getTexte());
                $id++;
            }

            //VERIFICATION MAJ DE L ACTIVITE DU USER

            $em = $this->getDoctrine()->getManager();

            $now = new \DateTime();
            $user->setDerniereActivite($now);
            $em->persist($user);
            $em->flush();

            $userList = $repositoryUser->findBy(array("partie" => $partie));

            //VERIFICATION : SUPPRESSION INACTIF
            $createur_supprime = false;
            foreach ($userList as $ul) {
                if (($now->getTimestamp() - $ul->getDerniereActivite()->getTimestamp()) > 10) {
                    if ($partie->getCreateur() == $ul) {
                        $partie->setCreateur(NULL);
                        $createur_supprime = true;
                    }
                    $ul->getUser()->setUserCourant(NULL);
                    $em->persist($userGlobal);
                    $em->remove($ul);
                    $em->flush();
                }
            }
            if ($createur_supprime) {
                $userCreateur = $repositoryUser->findOneBy(array("partie" => $partie));
                $partie->setCreateur($userCreateur);
                $em->persist($partie);
                $em->flush();
            }

            //LISTE DES UTILISATEURS
            $userData = array();
            $createur = false;
            $id = 0;
            $userList = $repositoryUser->findBy(array("partie" => $partie));
            foreach ($userList as $ul) {
                if ($ul == $partie->getCreateur()) {
                    $userData[$id] = $ul->getUser()->getUsername() . " - Créateur";
                    if ($partie->getCreateur() == $user) {
                        $createur = true;
                    }
                } else {
                    $userData[$id] = $ul->getUser()->getUsername();
                }
                $id++;
            }

            //VERIFICATION: PARTIE LANCEE
            if ($partie->isCommencee()) {
                $lancer = true;
            } else {
                $lancer = false;
            }

            return new JsonResponse(array('messages' => $data, 'users' => $userData, 'createur' => $createur, 'lancer' => $lancer, 'param' => $partie->getParametres()->getId(), 'compo' => $partie->getComposition()->getId()));
        }
        return new JsonResponse();
    }
}