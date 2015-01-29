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


        $user = $repositoryUser->findOneBy(array("user" => $this->getUser()));
        $partie = $user->getPartie();
        $chat = $partie->getChat();

        $newMessage = new Message();
        $newMessage->setChat($chat);
        $newMessage->setDate(new \DateTime());
        $newMessage->setTexte($message);
        $newMessage->setUser($this->getUser());

        $em->persist($newMessage);
        $em->flush();

        return new JsonResponse();

    }

    public function recevoirMessageAction(){
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $repositoryUser->findOneBy(array("user" => $this->getUser()));
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

        $response = new JsonResponse(array('messages' => $data));

        return $response;
    }
}