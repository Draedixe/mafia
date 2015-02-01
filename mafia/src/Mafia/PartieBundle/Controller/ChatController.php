<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatController extends Controller{

    public function envoyerMessageAction(){
        $request = $this->container->get('request');
        $message = $request->get('message');

        $repositoryMessage = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Message');
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');
        $em = $this->getDoctrine()->getManager();


        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user == null){
            return new JsonResponse(array('messages' => array(), 'users' => array()));
        }
        $partie = $user->getPartie();
        $chat = $partie->getChat();

        $newMessage = new Message();
        $newMessage->setChat($chat);
        $newMessage->setDate(new \DateTime());
        $newMessage->setTexte($message);
        $newMessage->setUser($this->getUser());

        $em->persist($newMessage);
        $em->flush();

        //LISTE DES UTILISATEURS
        $userList = $repositoryUser->findBy(array("partie"=>$partie));
        $userData = array();
        $id = 0;
        foreach($userList as $ul){
            if($ul == $partie->getCreateur()){
                $userData[$id] = $ul->getUser()->getUsername() . " - Créateur";
            }
            else{
                $userData[$id] = $ul->getUser()->getUsername();
            }
            $id ++;
        }
        //Messages
        $idPremier = $request->get('premierid');
        $messages = $repositoryMessage->myFind($chat,$idPremier);

        $data = array();
        $id = 0;
        foreach($messages as $message){
            $data[$id] = array("id"=>$message->getId(),"pseudo"=>$message->getUser()->getUsername(),"message"=>$message->getTexte());
            $id++;
        }

        return new JsonResponse(array('messages' => $data, 'users' => $userData));
    }

    public function recevoirMessageAction(){
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser(), "vivant" => true));
        if($user == null){
            return new JsonResponse(array('messages' => array(), 'users' => array()));
        }
        $partie = $user->getPartie();
        $chat = $partie->getChat();

        $repositoryMessage = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Message');

        $request = $this->container->get('request');
        $id = $request->get('premierid');

        $messages = $repositoryMessage->myFind($chat,$id);

        $data = array();
        $id = 0;
        foreach($messages as $message){
            $data[$id] = array("id"=>$message->getId(),"pseudo"=>$message->getUser()->getUsername(),"message"=>$message->getTexte());
            $id++;
        }

        //VERIFICATION MAJ DE L ACTIVITE DU USER

        $em = $this->getDoctrine()->getManager();

        $now = new \DateTime();
        $user->setDerniereActivite($now);
        $em->persist($user);
        $em->flush();

        $userList = $repositoryUser->findBy(array("partie"=>$partie));

        //VERIFICATION : SUPPRESSION INACTIF
        $createur_supprime = false;
        foreach($userList as $ul){
            if(($now->getTimestamp()- $ul->getDerniereActivite()->getTimestamp()) > 10){
                if($partie->getCreateur() == $ul){
                    $partie->setCreateur(NULL);
                    $createur_supprime = true;
                }
                $em->remove($ul);
                $em->flush();
            }
        }
        if($createur_supprime){
            $userCreateur = $repositoryUser->findOneBy(array("partie"=>$partie));
            $partie->setCreateur($userCreateur);
            $em->persist($partie);
            $em->flush();
        }

        //LISTE DES UTILISATEURS
        $userData = array();
        $createur = false;
        $id = 0;
        $userList = $repositoryUser->findBy(array("partie"=>$partie));
        foreach($userList as $ul){
            if($ul == $partie->getCreateur()){
                $userData[$id] = $ul->getUser()->getUsername() . " - Créateur";
                if($partie->getCreateur() == $user){
                    $createur = true;
                }
            }
            else{
                $userData[$id] = $ul->getUser()->getUsername();
            }
            $id ++;
        }

        //VERIFICATION: PARTIE LANCEE
        if($partie->isCommencee()){
            $lancer = true;
        }
        else{
            $lancer = false;
        }

        return new JsonResponse(array('messages' => $data, 'users' => $userData, 'createur' => $createur, 'lancer' => $lancer));
    }
}